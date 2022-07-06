<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'description', 'price', 'admin_id'];

    public function scopeFilter($query, array $filters) {
        if($filters['q'] ?? false) {
            $query->where(fn($query) => $query->where('name', 'like', '%' . $filters['q'] . '%')->orWhere('description', 'like', '%' . $filters['q'] . '%'));
        }

        $priceOperator = '<=';
        $allowedPriceOperators = [
            'eq' => '=',
            'gt' => '>',
            'gte' => '>=',
            'lt' => '<',
            'lte' => '<='
        ];

        if($filters['operator'] ?? false) {
            forEach($allowedPriceOperators as $op => $val) {
                global $priceOperator;
                if($op == request('operator')) {
                    $priceOperator = $val;
                }
            }
        }
        
        if($filters['price'] ?? false) {
            $query->where(fn($query) => $query->where('price', $priceOperator, $filters['price']));
        }
    }
        public function users() {
            return $this->belongsToMany(User::class);
        }

        
}
