<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\NetworkModel;
use Illuminate\Http\Request;
use App\Models\web\Network;
use App\Models\web\NetworkCommissionList;
use App\Models\web\NetworkCommissionType;
use App\Models\web\NetworkfrequencyList;
use App\Models\web\NetworkPaymentList;
use App\Models\web\NetworkPaymentMethod;
use App\Models\web\NetworkPayoutFrequencyi;
use App\Models\web\NetworkSocialPage;
use App\Models\web\NetworkSoftware;
use App\Models\web\NetworkVertical;
use App\Models\Admin\SocialSiteListList;
use App\Models\Admin\UserDetail;
use App\Models\Admin\Vertical;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class NetworksController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Network::all();
        return view('Admin.networks.index_networks', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //

        $data = [];

        $data['verticals'] = Vertical::pluck('title', 'id'); //all();

        $data['network_softwares'] = NetworkSoftware::pluck('name', 'id');

        $data['commission_type'] = NetworkCommissionList::pluck('name', 'id');

        $data['payment_method'] = NetworkPaymentList::pluck('name', 'id');
        $data['payment_frequency'] = NetworkfrequencyList::pluck('name', 'id');
        // return view('web.frontend.register', $data);
        return view('web.pages.registration.register', $data);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //

        $validated = $request->validate([
            'network_name' =>  'required|unique:networks|max:255',
            'network_type' => 'required',
            'network_url' => 'required',
            'network_description' => 'required',
            'offer_count[]' => 'required',
            'vertical_id.*'  => 'required',
            'commission_type.*'  => 'required',
            'min_payout' => 'required',
            'payment_frequency' => 'required|max:10',
            'payment_method.*' => 'required',
            'affiliate_tracking_software' => 'required',
            'social_site.*' => 'required',
            'social_link.*' => 'required',
            'name'  => 'required',
            'email' =>  'required|unique:users|max:255|email',
            'phone_number'  => 'required',
            'image' => 'required|image|max:2048',
            // |active_url
        ]);

        $id = $this->createUser($request);
        $this->createUserDetails($request, $id);
        $this->createNetwork($request, $id);

        // $network  = $request->all();
        // Network::create($network);
        // return redirect('users')->with('message', 'Your data successfully added'); kh
    }
    //for social site select box and link input so that multiple site can be added
    public function set_social_sites(Request $req, $network_id = 0)
    {
        $social_sites = $req->input('social_site');
        $social_links = $req->input('social_link');

        $ss = [];
        foreach ($social_sites as $key => $value) {
            $ss[] = [
                'network_id' => $network_id,
                'social_site' => $value,
                'social_link' => $social_links[$key],
            ];
        }
        //(
        NetworkSocialPage::insert($ss);
    }

    //for top verticals select box
    public function set_verticals(Request $req, $network_id = 0)
    {
        $vertical_id = $req->input('vertical_id');

        $ss = [];
        foreach ($vertical_id as $key => $value) {
            $ss[] = [
                'network_id' => $network_id,
                'vertical_id' => $value,
            ];
        }
        //
        NetworkVertical::insert($ss);
    }




    public function set_networks_commission(Request $req, $id)
    {
        $commission_type = $req->input('commission_type');

        $ss = [];
        foreach ($commission_type as $key => $value) {
            $ss[] = [

                'network_id' => $id,
                'commission_type' => $value,
            ];
        }
        //11
        NetworkCommissionType::insert($ss);
    }


    public function set_networks_payment(Request $req, $id)
    {
        $payment_method = $req->input('payment_method');

        $ss = [];
        foreach ($payment_method as $key => $value) {
            $ss[] = [
                'network_id' => $id,
                'payment_method' => $value,
            ];
        }
        //11
        NetworkPaymentMethod::insert($ss);
    }


    public function set_payout_frequency(Request $req, $id)
    {
        $payment_frequency = $req->input('payment_frequency');

        $ss = [];
        foreach ($payment_frequency as $key => $value) {
            $ss[] = [
                'network_id' => $id,
                'payment_frequency' => $value,
            ];
        }
        //11
        NetworkPayoutFrequencyi::insert($ss);
    }





    public function upload(Request $request)
    {
        $path = $request->file('image')->store('images');

        Network::insert($path);
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }
    //for users table 
    private function createUser(Request $request)
    {
        $user = $request->only(['name', 'email']);
        //pass password randomly
        $user['password'] =  Hash::make(rand(1, 1000));
        $new_user = User::create($user);
        return $new_user->id;
    }
    // $id = $this->createUser($request); already called in store method

    private function createUserDetails(Request $request, $id)
    {
        $userDetails = $request->all();
        $userDetails['user_id'] = $id;
        $userDetails = UserDetail::create($userDetails);
    }
    // $this->createUserDetails($request, $id); already called in store method
    private function createNetwork(Request $request, $id)
    {
        $networks = $request->all();
        $networks['user_id'] = $id;

        $path = $request->file('image')->store('images');
        $networks['logo'] = $path;



        $network = Network::create($networks);

        $this->set_social_sites($request, $network->network_id);

        $this->set_verticals($request, $network->network_id);



        $this->set_networks_commission($request, $network->network_id);
        $this->set_networks_payment($request, $network->network_id);
        $this->set_payout_frequency($request, $network->network_id);




        //network commission table

        // $commission_type = [
        //     'network_id' => $network->network_id,
        //     'commission_type' => $request->input('commission_type'),
        // ];
        // $commission_type = NetworkCommissionType::create($commission_type);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $network_id)
    {
        $data = [];
        $data['network'] = Network::where('network_id', $network_id)->first();
        $data['network_id'] = $network_id;
        return view('Admin.networks.update_networks', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $network_id)
    {
        //
        $validated = $request->validate([
            'network_name' =>  "required|unique:networks,network_id," . $network_id . ",network_id|max:255",
            'network_type' => 'required',
            'network_url' => 'required',
            'network_description' => 'required',
            'offer_count' => 'required',
            'min_payout' => 'required',
            'affiliate_tracking_software' => 'required',
            'social_site.*' => 'required',
            'social_link.*' => 'required',
        ]);

        $network = Network::where('network_id', $network_id)->first();
        $network->network_name = $request->input('network_name');
        $network->network_type = $request->input('network_type');
        $network->network_url = $request->input('network_url');
        $network->network_description = $request->input('network_description');
        $network->offer_count = $request->input('offer_count');
        $network->min_payout = $request->input('min_payout');
        $network->affiliate_tracking_software = $request->input('affiliate_tracking_software');
        $network->social_site = $request->input('social_site');
        $network->social_link = $request->input('social_link');
        $network->image = $request->input('image');
        $network->save();
        return redirect('networks')->with('message', 'Your data successfully updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $network_id)
    {
        // dd($network_id);
        $network = Network::where('network_id', $network_id)->first();

        $network->delete();
        return redirect('networks')->with('message', 'Your data has been successfully deleted');
    }
}
