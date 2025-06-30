<?php

namespace App\Services;

use App\Models\Booking;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Query\Builder;
class BookingService
{
    public function createBatchBookings(int $roomId, array $bookings): array
    {
        //complete this call

        $conflicts = DB::table('bookings');
        
        foreach($bookings as $index => $booking) {
            // Set room_id value as it is missing in each $booking
            $bookings[$index]["room_id"] = $roomId;
            // Add orWhere query to check if booking has overlap to query builder
            $conflicts = $conflicts->orWhere(function (Builder $query) use ($roomId,$booking){
                $query->where('room_id', '=',$roomId)
                ->where('start_time', '<=', $booking['end_time'])
                ->where('end_time', '>=', $booking['start_time']);
            });
        }
        // If there are conflicts, return a negative resposne
        if(!$conflicts->get()->isEmpty())return ['success' => false, 'conflicts' => ['error' => '']];
        
        // Insert new bookings in table
        DB::table("bookings")->insert($bookings);
        // Return positive response
        return ['success' => true, 'conflicts' => []];
    }
    
    
}
