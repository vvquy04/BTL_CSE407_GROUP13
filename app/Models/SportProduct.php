<?php

namespace App\Models;

use App\Interfaces\IProduct;

class SportProduct extends Product implements IProduct
{
    protected $table = 'tbl_sport_products';
    
    protected $fillable = [
        'category_id',
        'branch_id',
        'product_content',
        'product_keywords',
        'product_desc',
        'product_price',
        'product_image',
        'product_name',
        'product_status',
        'water_resistance',
        'sport_features',
        'material',
        'movement_type',
        'case_material'
    ];

    protected $casts = [
        'sport_features' => 'array'
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->attributes['type'] = 'Sport';
    }

    public function getName(): string
    {
        return $this->product_name;
    }

    public function getPrice(): float
    {
        return (float) $this->product_price;
    }

    public function getDescription(): string
    {
        return $this->product_desc;
    }

    public function getCategory(): string
    {
        return $this->category->category_name ?? '';
    }

    public function getStock(): int
    {
        return $this->product_quantity ?? 0;
    }

    public function getImage(): string
    {
        return $this->product_image;
    }

    public function getStatus(): int
    {
        return (bool) $this->product_status;
    }

    public function getBrand(): string
    {
        return $this->brand->brand_name ?? '';
    }

    public function getModel(): string
    {
        return $this->product_model ?? '';
    }

    public function getMovement(): string
    {
        return $this->movement_type ?? '';
    }

    public function getGender(): string
    {
        return $this->gender ?? '';
    }

    public function getType(): string
    {
        return 'Sport';
    }

    public function getWaterResistance(): string
    {
        return $this->water_resistance ?? '';
    }

    public function getSportFeatures(): array
    {
        return $this->sport_features ?? [];
    }

    public function getMaterial(): string
    {
        return $this->material ?? '';
    }

    public function getOS(): string
    {
        return '';
    }

    public function getBatteryLife(): string
    {
        return '';
    }

    public function getFeatures(): array
    {
        return [];
    }
} 