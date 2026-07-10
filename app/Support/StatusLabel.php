<?php

namespace App\Support;

class StatusLabel
{
    public static function order(?string $status): string
    {
        return self::map($status, [
            'pending' => 'Pendiente',
            'confirmed' => 'Confirmado',
            'paid' => 'Pagado',
            'processing' => 'En proceso',
            'preparing' => 'En preparacion',
            'ready_to_ship' => 'Listo para despacho',
            'shipped' => 'Enviado',
            'delivered' => 'Entregado',
            'completed' => 'Completado',
            'cancelled' => 'Cancelado',
            'refunded' => 'Reembolsado',
            'failed' => 'Fallido',
        ]);
    }

    public static function payment(?string $status): string
    {
        return self::map($status, [
            'pending' => 'Pendiente',
            'paid' => 'Pagado',
            'partially_paid' => 'Parcialmente pagado',
            'failed' => 'Fallido',
            'refunded' => 'Reembolsado',
            'unpaid' => 'No pagado',
        ]);
    }

    public static function fulfillment(?string $status): string
    {
        return self::map($status, [
            'pending' => 'Pendiente',
            'picking' => 'Picking',
            'picked' => 'Preparado',
            'packing' => 'Packing',
            'packed' => 'Empacado',
            'ready' => 'Listo',
            'shipped' => 'Enviado',
            'delivered' => 'Entregado',
            'cancelled' => 'Cancelado',
            'missing' => 'Faltante',
        ]);
    }

    public static function generic(?string $status): string
    {
        return self::map($status, [
            'active' => 'Activo',
            'inactive' => 'Inactivo',
            'pending' => 'Pendiente',
            'approved' => 'Aprobado',
            'rejected' => 'Rechazado',
            'completed' => 'Completado',
            'cancelled' => 'Cancelado',
            'failed' => 'Fallido',
            'success' => 'Exitoso',
        ]);
    }

    private static function map(?string $status, array $labels): string
    {
        if ($status === null || $status === '') {
            return '-';
        }

        return $labels[$status] ?? str($status)->replace('_', ' ')->headline()->toString();
    }
}
