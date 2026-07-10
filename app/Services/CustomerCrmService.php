<?php

namespace App\Services;

use App\Models\CustomerAddress;
use App\Models\CustomerCompany;
use App\Models\CustomerDocument;
use App\Models\CustomerNote;
use App\Models\CustomerProfile;
use App\Models\CustomerRewardTransaction;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CustomerCrmService
{
    public function createCustomer(array $profileData, array $tagIds = [], ?string $password = null): CustomerProfile
    {
        return DB::transaction(function () use ($profileData, $tagIds, $password) {
            $user = User::query()->create([
                'name' => trim(($profileData['first_name'] ?? '').' '.($profileData['last_name'] ?? '')) ?: $profileData['email'],
                'email' => $profileData['email'],
                'phone' => $profileData['phone'] ?? null,
                'password' => Hash::make($password ?: Str::random(16)),
                'is_active' => $profileData['is_active'] ?? true,
                'role_id' => $profileData['role_id'] ?? null,
            ]);

            $profile = $user->customerProfile()->create($this->prepareConsent($profileData));
            $profile->tags()->sync($tagIds);

            AuditLogger::record('created', 'customers', "Cliente creado: {$profile->email}");

            return $profile;
        });
    }

    public function updateCustomer(CustomerProfile $profile, array $profileData, array $tagIds = []): CustomerProfile
    {
        return DB::transaction(function () use ($profile, $profileData, $tagIds) {
            $profile->update($this->prepareConsent($profileData));
            $profile->user?->update([
                'name' => trim(($profileData['first_name'] ?? '').' '.($profileData['last_name'] ?? '')) ?: $profileData['email'],
                'email' => $profileData['email'],
                'phone' => $profileData['phone'] ?? null,
                'is_active' => $profileData['is_active'] ?? $profile->is_active,
            ]);
            $profile->tags()->sync($tagIds);

            AuditLogger::record('updated', 'customers', "Cliente editado: {$profile->email}");

            return $profile->refresh();
        });
    }

    public function saveAddress(CustomerProfile $profile, array $data, ?CustomerAddress $address = null): CustomerAddress
    {
        return DB::transaction(function () use ($profile, $data, $address) {
            if (($data['is_default'] ?? false) === true || ($data['is_default'] ?? false) === '1') {
                $profile->addresses()
                    ->where('address_type', $data['address_type'])
                    ->when($address, fn ($query) => $query->whereKeyNot($address->getKey()))
                    ->update(['is_default' => false]);
            }

            $saved = $address ? tap($address)->update($data) : $profile->addresses()->create($data);
            AuditLogger::record($address ? 'updated' : 'created', 'customer_addresses', "Dirección actualizada para {$profile->email}");

            return $saved;
        });
    }

    public function saveCompany(CustomerProfile $profile, array $data, ?CustomerCompany $company = null): CustomerCompany
    {
        return DB::transaction(function () use ($profile, $data, $company) {
            $saved = $company ? tap($company)->update($data) : $profile->companies()->create($data);
            AuditLogger::record($company ? 'updated' : 'created', 'customer_companies', "Empresa actualizada para {$profile->email}");

            return $saved;
        });
    }

    public function storeDocument(CustomerProfile $profile, UploadedFile $file, array $data): CustomerDocument
    {
        return DB::transaction(function () use ($profile, $file, $data) {
            $path = $file->store('customer-documents', 'public');
            $document = $profile->documents()->create([
                'document_type' => $data['document_type'],
                'description' => $data['description'] ?? null,
                'file_path' => $path,
            ]);

            AuditLogger::record('uploaded', 'customer_documents', "Documento cargado para {$profile->email}");

            return $document;
        });
    }

    public function deleteDocument(CustomerDocument $document): void
    {
        DB::transaction(function () use ($document) {
            Storage::disk('public')->delete($document->file_path);
            $email = $document->customerProfile?->email;
            $document->delete();
            AuditLogger::record('deleted', 'customer_documents', "Documento eliminado para {$email}");
        });
    }

    public function addRewardTransaction(CustomerProfile $profile, array $data): CustomerRewardTransaction
    {
        return DB::transaction(function () use ($profile, $data) {
            $points = (float) $data['points'];
            $delta = $data['type'] === 'redeem' ? -abs($points) : $points;

            $transaction = $profile->rewardTransactions()->create($data);
            $profile->increment('reward_points', $delta);

            AuditLogger::record('created', 'customer_rewards', "Movimiento de puntos para {$profile->email}: {$delta}");

            return $transaction;
        });
    }

    public function addNote(CustomerProfile $profile, array $data): CustomerNote
    {
        $note = $profile->notes()->create([
            'user_id' => auth()->id(),
            'note' => $data['note'],
            'is_private' => $data['is_private'] ?? true,
        ]);

        AuditLogger::record('created', 'customer_notes', "Nota agregada para {$profile->email}");

        return $note;
    }

    private function prepareConsent(array $data): array
    {
        $consentFields = ['newsletter', 'accept_promotions', 'accept_sms', 'accept_whatsapp', 'accept_email_marketing', 'accept_cookies'];

        foreach ($consentFields as $field) {
            $data[$field] = (bool) ($data[$field] ?? false);
        }

        if (collect($consentFields)->contains(fn ($field) => $data[$field])) {
            $data['consent_accepted_at'] ??= now();
        }

        return $data;
    }
}
