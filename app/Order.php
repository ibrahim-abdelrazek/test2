<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon;
class Order extends Model
{
    static $model = 'order';

    protected $fillable = [
         'id',
         'prescription',
         'notes',
         'patient_id',
         'doctor_id',
         'partner_id',
         'products',
         'copayments',
         'prices',
         'total',
         'totaldiscount',
         'canceled',
         'status_id',
         'user_id',
         'insurance_claim'
    ];
    protected $casts = [
        'products' => 'array',
        'copayments' => 'array',
        'prices' => 'array',
        'canceled' => 'array'
    ];
    protected $appends = ['name', 'address', 'partner', 'FormattedTotal' ,'CreatedDate', 'UpdatedDate', 'InprocessDate', 'DispatchedDate', 'DeliveredDate','ConfirmedDate', 'status', 'Orders', 'OrderImageId'];
   

     public function getDates()
    
    {
        return ['created_at', 'updated_at'];
    }

    /**
     * @return string
     */
    public function getCreatedAtAttribute()
    {
        return  Carbon::parse($this->attributes['created_at'])->diffForHumans();
    }

    /**
     * @return string
     */
    public function getUpdatedAtAttribute()
    {
        return  Carbon::parse($this->attributes['updated_at'])->diffForHumans();
    }

    public function partner (){
    	return $this->belongsTo(Partner::class);
    }
    public function product (){
    	return $this->belongsTo(Product::class);
    }
    public function doctor (){
    	return $this->belongsTo(Doctor::class);
    }
    public function patient (){
    	return $this->belongsTo(Patient::class);
    }
    public function transactions (){
    	return $this->hasOne(Transaction::class);
    }
    public function Owner()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function status(){
        return $this->belongsTo(Status::class);
    }
    public function getNameAttribute()
    {
        return $this->patient->name;  
    }
    public function getAddressAttribute()
    {
        return $this->patient->city->city_name ;  
    }
    public function getPartnerAttribute()
    {
        $partner = Partner::where('id', $this->partner_id)->first();
        return  $partner->first_name . ' ' . $partner->last_name;  
    }
    public function getStatusAttribute(){
        return Status::where('id', $this->status_id)->first();
    }
    public function getOrdersAttribute(){
        $orders = $this->products;
        if(count($orders) > 0){
            $array = [];
            foreach ($orders as $key => $value) {
                $product = Product::find($key);
                if(!empty($product) && !empty($this->prices[$key])){
                    $canceled = false;
                    if(!empty($this->canceled) && array_key_exists($key, $this->canceled))
                        $canceled = true;

                    $priceDiscount = ($this->copayments[$key] > 0)? $value * $this->prices[$key] * ($this->copayments[$key] / 100) : $value * $this->prices[$key];
                    $array[] = array_merge($product->toArray(), [
                        'Quantity'=>(int) $value,
                        'FormattedPricePerItem'=> $this->prices[$key],
                        'FormattedPrice'=> $priceDiscount ,
                        'FormattedDiscount'=> ($value * $this->prices[$key]) - $priceDiscount,
                        'sum' => $value * $this->prices[$key],
                        'IsCanceled'=>$canceled]
                    );
                }
            }

            //var_dump($array);die;
            return $array;
        }
        return;
    }
    public function getOrderImageIdAttribute()
    {
        return asset($this->prescription);
    }
    public function getFormattedTotalAttribute()
    {
        $total = 0;
        if(!empty($this->products)){
            $products = !empty($this->canceled) ? array_diff_key($this->products , $this->canceled) : $this->products;
            if(!empty($products)){
               foreach($products as $product=>$value){
                    //$prod = Product::find($product);
                    if(isset($this->prices[$product])){
                        $total += ($this->copayments[$product] > 0)? $value * $this->prices[$product]  * ($this->copayments[$product] / 100) : $value * $this->prices[$product];
                    }
               }
            }
        }
        
        return $total;
    }
     public function getCreatedDateAttribute(){

        return "/Date(" .strtotime($this->created_at).")/";
     }
    public function getUpdatedDateAttribute(){
        return "/Date(".strtotime($this->updated_at) . ")/";
    }
    public function getConfirmedDateAttribute()
    {
        $stat = OrderStatus::where('order_id', $this->id)->where('status_id', 1)->first();
        if(!empty($stat))
            return "/Date(".strtotime($stat->created_at) . ")/";
        return "/Date(-".strtotime($this->updated_at) . ")/"; 
    }
    public function getInprocessDateAttribute(){

        $stat = OrderStatus::where('order_id', $this->id)->where('status_id', 2)->first();
        if(!empty($stat))
            return "/Date(".strtotime($stat->created_at) . ")/";
        return "/Date(-".strtotime($this->updated_at) . ")/";     }
    public function getDispatchedDateAttribute(){

        $stat = OrderStatus::where('order_id', $this->id)->where('status_id', 3)->first();
        if(!empty($stat))
            return "/Date(".strtotime($stat->created_at) . ")/";
        return "/Date(-".strtotime($this->updated_at) . ")/";     }
    public function getDeliveredDateAttribute(){

        $stat = OrderStatus::where('order_id', $this->id)->where('status_id', 4)->first();
        if(!empty($stat))
            return "/Date(".strtotime($stat->created_at) . ")/";
        return "/Date(-".strtotime($this->updated_at) . ")/";    
         }
    
}
