<?php
namespace App\Http\Filters\V1;

class  TicketFilter extends QueryFilter
{
    protected $sortable = ['id', 'title', 'status', 'createdAt'=>'created_at', 'updatedAt' => 'updated_at'];

    public function status($value)
    {
        return $this->builder->whereIn('status', explode(',', $value));
    }

    public function createdAt($value)
    {
        $dates = explode(',', $value);
        if (count($dates) === 2) {
            return $this->builder->whereBetween('created_at', $dates);
        } elseif (count($dates) > 2) {
            return $this->builder->whereIn('created_at', $dates);
        }
        return $this->builder->whereDate('created_at', $value);
    }

    public function include($value)
    {
        return $this->builder->with($value);
    }

    public function title($value)
    {
        return $this->builder->where('title', 'like', "%$value%");
    }

    public function updatedAt($value)
    {
        $dates = explode(',', $value);
        if (count($dates) === 2) {
            return $this->builder->whereBetween('updated_at', $dates);
        } elseif (count($dates) > 2) {
            return $this->builder->whereIn('updated_at', $dates);
        }
        return $this->builder->whereDate('updated_at', $value);
    }
}
