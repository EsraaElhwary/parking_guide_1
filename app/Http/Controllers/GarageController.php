<?php


namespace App\Http\Controllers;

use JWTAuth;
use App\Models\User;
use App\Models\Garage;
use App\Models\Comment;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Controllers\ConmmentController;


class GarageController extends Controller
{
    protected $user;
 
    public function __construct()
    {
        $this->user = JWTAuth::parseToken()->authenticate();
    }

    // retutn all garages of current user using function:get_owner_garages / in user model
    public function index()
    {   
       return User::find(auth()->id())->get_owner_garages;
    }

    // get a garage with it's comments
    public function show_One_garage($id)
    {   
        
        $garage = Garage::find($id);
        if ($garage->owner_id !==  auth()->id()){
            abort(403);
        } 
        else {
            return Garage::find($id)->load('comments');
        }
        
    }

    // show all garages for any user (not the auth)
    public function show_all_garages()
    {   
        return Garage::all();
        
    }

    // show garage by id for any user (not the auth)
    public function show_garage($id)
    {
        return Garage::find($id);
    }

    public function store(Request $request)
    {
       
        $this->validate($request, [
           // 'id' => 'required',
            'name' => 'required',
            'city' => 'required',
            'street' => 'required',
            'b_number' => 'required',
            'capacity' => 'required',
            'lat'      =>  'required',
            'long'     =>  'required',
           
        ]);
            
        // Create using when you have timestamps auto insert
        $garage = Garage::create([
            'name' => $request->name,
            'city' => $request->city,
            'street' => $request->street,
            'b_number' => $request->b_number,
            'capacity' => $request->capacity,
            'user_id' => $request->user()->id,
            'lat'     => $request->lat,
            'long' => $request->long,
        ]);

    
        if($garage) {
            return response()->json([
                
                'success' => true,
                'garage' => $garage  
                
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, garage could not be added'
            ], 500);
        }
    }


    

    public function update(Request $request, $id)
    {

        $garage = Garage::find($id);
        // check user is the real owner of garage
        if ($garage->owner_id !==  auth()->id()){
                abort(403);
            } else
            {
  
                $garage->name = $request->name;
                $garage->city = $request->city;
                $garage->street = $request->street;
                $garage->b_number = $request->b_number;
                $garage->capacity = $request->capacity;
                $garage->lat = $request->lat;
                $garage->long = $request->long;
                $garage->save();
                
                if ($garage->update()) {
                    return response()->json(['status' => 'success']);
                } else {
                    return response()->json(['status' => 'can not be updated']);
                }
              }
        
    }

    public function destroy( $id)
    {
    
        $garage = Garage::find($id);

        if ($garage->owner_id !==  auth()->id()){
            abort(403);
        } 
        else {
            if ($garage -> delete()) 
            {
                return response() -> json(['status' => 'success']);
            } else {
                return response() -> json(['status' => 'can not be updated']);
            }
        }


        
    }

    // find the garage{id} , it's requests : requestcars.  and filter : status=10
    public function get_garage_active_requests($id) {
        $garage = Garage::find($id);
        if ($garage->owner_id !==  auth()->id()){
            abort(403);
        } else  {
            return Garage::where('id', $id)->with(['requestcars' => function($query) {
                $query->where('status', 10);
            }])->first(); }
    }
  

}
