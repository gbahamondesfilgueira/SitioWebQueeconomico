<?php

return [
    'volumetric_factor' => env('PRODUCT_VOLUMETRIC_FACTOR', 4000),
    'max_image_size_kb' => env('PRODUCT_IMAGE_MAX_SIZE_KB', 2048),
    'allowed_image_mimes' => ['jpg', 'jpeg', 'png', 'webp'],
];
