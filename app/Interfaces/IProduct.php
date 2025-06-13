<?php

namespace App\Interfaces;

interface IProduct
{
    public function getName(): string;
    public function getPrice(): float;
    public function getDescription(): string;
    public function getStatus(): int;
    public function getImage(): string;
    public function getCategory(): string;
    public function getBrand(): string;
    public function getStock(): int;
    public function getModel(): string;
    public function getMovement(): string;
    public function getGender(): string;
    public function getType(): string;
    public function getOS(): string;
    public function getBatteryLife(): string;
    public function getFeatures(): array;
    public function getWaterResistance(): string;
    public function getSportFeatures(): array;
    public function getMaterial(): string;
} 