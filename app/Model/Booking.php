<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use App\Model\hordingimages;
use DB;

class Booking extends Model {

    protected $table = 'hoadingmaster';

    public function getdatatable() {
        $requestData = $_REQUEST;

        $columns = array(
            // datatable column index  => database column name
            1 => 'hoadingmaster.id',
            0 => 'hoadingmaster.location',
            2 => 'hoadingmaster.startdate',
            3 => 'hoadingmaster.enddate',
            4 => 'hoadingmaster.status',
            5 => 'hoadingmaster.type',
            6 => 'hoadingmaster.email',
            7 => 'hoadingmaster.budget',
            8 => 'hoadingmaster.cart',
            9 => 'hoadingimages.hoadingid',
        );

        $query = Booking::from('hoadingmaster')
                ->leftjoin("hoadingimages", "hoadingimages.hoadingid", "=", "hoadingmaster.id")
                ->groupBy('hoadingimages.hoadingid');

        if (!empty($requestData['search']['value'])) {
            // if there is a search parameter, $requestData['search']['value'] contains search parameter
            $searchVal = $requestData['search']['value'];
            $query->where(function($query) use ($columns, $searchVal, $requestData) {
                $flag = 0;
                foreach ($columns as $key => $value) {
                    $searchVal = $requestData['search']['value'];
                    if ($requestData['columns'][$key]['searchable'] == 'true') {
                        if ($flag == 0) {
                            $query->where($value, 'like', '%' . $searchVal . '%');
                            $flag = $flag + 1;
                        } else {
                            $query->orWhere($value, 'like', '%' . $searchVal . '%');
                        }
                    }
                }
            });
        }
        // print_r($requestData);exit;
//             $temp = '';
        $temp = $query->orderBy($columns[$requestData['order'][0]['column']], $requestData['order'][0]['dir']);

        $totalData = count($temp->get());
        $totalFiltered = count($temp->get());

        $resultArr = $query->skip($requestData['start'])
                ->take($requestData['length'])
                ->select('hoadingimages.hoadingid', 'hoadingimages.imagename', 'hoadingmaster.*')
                ->get();

        $type = Config::get('constants.hoarding_type');
        $i = 0;
        $data = array();

        foreach ($resultArr as $row) {
            $i++;
            $checkbox = "<input type='checkbox' name='hoardingid[]' > ";
            $actionHtml = '<a href="' . route('edit-hoarding', $row['id']) . '" ><button class="btn btn-primary btn-xs">
                                       <i class="fa fa-pencil"></i>
                                    </button></a>
                                    <button class="btn btn-danger btn-xs deletehoarding" data-toggle="modal" data-target="#deleteModel" data-id="' . $row['id'] . '">
                                        <i class="fa fa-trash-o"></i>
                                    </button>';
            $image = '<img class="img-responsive" src="'.url("public/uploads/hoarding/".$row["imagename"]).'" alt="hoarding">';
            if ($row["status"] == 'Available') {
                $statushtml = '<span class="label label-info label-mini">Available</span>';
            } else {
                $statushtml = '<span class="label label-success  label-mini">Close</span>';
            }

            $nestedData = array();
            $nestedData[] = $i;
            $nestedData[] = $checkbox;
            $nestedData[] = $image;
            $nestedData[] = $row["location"];
            $nestedData[] = date("d-m-Y", strtotime($row["startdate"]));
            $nestedData[] = date("d-m-Y", strtotime($row["enddate"]));
            $nestedData[] = $statushtml;
            $nestedData[] = $type[$row["type"]];
            $nestedData[] = '<i class="fa fa-inr"></i> ' . $row["budget"];
            $nestedData[] = $row["cart"];
            $nestedData[] = $actionHtml;
            $data[] = $nestedData;
        }
        //echo "<pre>";print_r($data);exit;

        $json_data = array(
            "draw" => intval($requestData['draw']), // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
            "recordsTotal" => intval($totalData), // total number of records
            "recordsFiltered" => intval($totalFiltered), // total number of records after searching, if there is no searching then totalFiltered = totalData
            "data" => $data   // total data array
        );
        return $json_data;
    }

    public function addHoarding($request) {
//          print_r($request->file());
//          die();
        $result = Booking::where("location", $request->input('location'))
                ->count();
        if ($result == 0) {
            $objBooking = new Booking();
            $objBooking->location = $request->input('location');
            $objBooking->startdate = date("Y-m-d", strtotime($request->input('startdate')));
            $objBooking->enddate = date("Y-m-d", strtotime($request->input('enddate')));
            $objBooking->status = $request->input('status');
            $objBooking->type = $request->input('type');
            $objBooking->budget = $request->input('budget');
            $objBooking->cart = 0;
            if ($objBooking->save()) {
                $bookingid = $objBooking->id;
                if ($request->file()) {
                    $totalimage = count(($request->file('hoarding')));
                    for ($i = 0; $i < $totalimage; $i++) {

                        $name = '';
                        $image = $request->file('hoarding')[$i];
                        $name = 'hoarding' . time() . $i . '.' . $image->getClientOriginalExtension();
                        $destinationPath = public_path('/uploads/hoarding');

                        if (!file_exists($destinationPath)) {
                            File::makeDirectory($destinationPath, $mode = 0777, true, true);
                        }

                        $image->move($destinationPath, $name);
                        $objHordingimages = new Hordingimages();
                        $objHordingimages->hoadingid = $bookingid;
                        $objHordingimages->imagename = $name;
                        $objHordingimages->save();
                    }
                }
                return "add";
            } else {
                return "wrong";
            }
        } else {
            return "exits";
        }
    }

    public function EditHoarding($request, $id) {

        $result = Booking::where("location", $request->input('location'))
                ->where('id', '!=', $id)
                ->count();
        if ($result == 0) {
            $objBooking = new Booking();
            $objBooking->location = $request->input('location');
            $objBooking->startdate = date("Y-m-d", strtotime($request->input('startdate')));
            $objBooking->enddate = date("Y-m-d", strtotime($request->input('enddate')));
            $objBooking->status = $request->input('status');
            $objBooking->type = $request->input('type');
            $objBooking->budget = $request->input('budget');
            $objBooking->cart = 0;
            if ($objBooking->save()) {
                $bookingid = $objBooking->id;
                if ($request->file()) {
                    $totalimage = count(($request->file('hoarding')));
                    for ($i = 0; $i < $totalimage; $i++) {

                        $name = '';
                        $image = $request->file('hoarding')[$i];
                        $name = 'hoarding' . time() . $i . '.' . $image->getClientOriginalExtension();
                        $destinationPath = public_path('/uploads/hoarding');

                        if (!file_exists($destinationPath)) {
                            File::makeDirectory($destinationPath, $mode = 0777, true, true);
                        }

                        $image->move($destinationPath, $name);
                        $objHordingimages = new Hordingimages();
                        $objHordingimages->hoadingid = $bookingid;
                        $objHordingimages->imagename = $name;
                        $objHordingimages->save();
                    }
                } else {
                    DB::table('hoadingimages')
                            ->where('hoadingid', $id)
                            ->update(['hoadingid' => $bookingid]);
                }
                Booking::where('id', '=', $id)->delete();
                return "add";
            } else {
                return "wrong";
            }
        } else {
            return "exits";
        }
    }

    public function gethoardingdetails($id) {

        $query = Booking::from('hoadingmaster')
                ->leftjoin("hoadingimages", "hoadingimages.hoadingid", "=", "hoadingmaster.id")
                ->where('hoadingmaster.id', $id)
//                    ->groupby('hoadingimages.hoadingid')
                ->select("*")
                ->get();
        return $query;
    }

    public function deletebooking($id) {
        $delete = Booking::where('id', $id)->delete();
        DB::table('hoadingimages')->where('hoadingid', $id)->delete();
        return $delete;
    }
}