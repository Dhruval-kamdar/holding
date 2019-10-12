@extends('admin.layout.app')
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card card-box">
            <div class="card-head">
                <header>Vender List</header>
                
            </div>
            <div class="card-body ">
                <div class="row p-b-20 pull-right">
                    <div class="col-md-6 col-sm-6 col-6">
                        <div class="btn-group">
                            <a href="{{ route("add-vender") }} " id="addRow" class="btn btn-info">
                                Add New <i class="fa fa-plus"></i>
                            </a>
                        </div>
                    </div>{{ csrf_field() }}
                </div>
                <div class="table-scrollable">
                    <table class="table table-hover table-checkable order-column full-width" id="datatable">
                        <thead>
                            <tr>
                                <th class="center">No</th>
                                <th class="center">Profile</th>
                                <th class="center">First Name </th>
                                <th class="center">Last Name </th>
                                <th class="center"> Email</th>
                                <th class="center">Mobile</th>
                                <th class="center">State </th>
                                <th class="center">City </th>
                                <th class="center">Address</th>
                                <th class="center">Update</th>
                                <th class="center">Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php 
                            $i =0;
                            @endphp
                            @foreach($result as $value)
                            @php
                            $i++;
                            @endphp
                            <tr class="odd gradeX">
                                <td class="center">{{ $i }}</td>
                                <td class="user-circle-img center">
                                    @if($value->profile != '' || $value->profile != NULL)
                                    <img height="50px" width="50px" src="{{ url('/uploads/profile/'.$value->profile) }}" alt="User's Profile Picture">
                                    @else
                                    <img  height="50px" width="50px" src="{{ url('public/admin/assets/img/mega-img1.jpg') }}" alt="User's Profile Picture">
                                    @endif
                                </td>
                                <td class="center">{{ $value->firstname }}</td>
                                <td class="center">{{ $value->lastname }}</td>
                                <td class="center">{{ $value->email }}</td>
                                <td class="center">{{ $value->mobileno }}</td>
                                <td class="center">{{ $value->state }}</td>
                                <td class="center">{{ $value->city }}</td>
                                <td class="center">{{ $value->address }}</td>
                                <td class="center">
                                    <a href="{{ route('edit-vender',$value->id) }}" class="btn btn-tbl-edit btn-xs">
                                        <i class="fa fa-pencil"></i>
                                    </a>
                                </td>
                                <td>
                                    <a data-toggle="modal" data-target="#deletemodal" data-id="{{ $value->id }}" class="btn btn-tbl-delete btn-xs delete"><i class="fa fa-trash-o"></i></a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!--delete toggle-->
<div class="modal fade" id="deletemodal" tabindex="-1" role="dialog" aria-labelledby="favoritesModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="favoritesModalLabel"></h4>
            </div>
            <div class="modal-body">
                <p>
                <h4>Are you sure?</h4>
                <b><span id="fav-title">You want to delete this record?</span></b> 
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default"  data-dismiss="modal">Close</button>
                <span class="pull-right">
                    <button type="button" class="btn btn-primary yes-sure" >DELETE</button>
                </span>
            </div>
        </div>
    </div>
</div>
@endsection