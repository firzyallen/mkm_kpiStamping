@extends('layouts.master')

@section('content')
<main>
    <header class="page-header page-header-dark bg-gradient-primary-to-secondary pb-10">
        <div class="container-fluid px-4">
            <div class="page-header-content pt-4">
                <!-- Optional Header Content -->
            </div>
        </div>
    </header>
    <!-- Main page content-->
    <div class="container-fluid px-4 mt-n10">
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <!-- Optional Content Header -->
            </section>
            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Master Welding Station</h3>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <div class="col-12">
                                            <button type="button" class="btn btn-dark btn-sm mb-2" data-bs-toggle="modal" data-bs-target="#modal-add">
                                                <i class="fas fa-plus-square"></i>
                                            </button>

                                            <!-- Modal -->
                                            <div class="modal fade" id="modal-add" tabindex="-1" aria-labelledby="modal-add-label" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="modal-add-label">Add Master Welding Station</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <form action="{{ url('/welding/station/store') }}" method="POST">
                                                            @csrf
                                                            <div class="modal-body">
                                                                <div class="form-group mb-3">
                                                                    <select name="shop_id" id="shop_id" class="form-control" required>
                                                                        <option value="">- Please Select Shop -</option>
                                                                        @foreach ($shopName as $shop)
                                                                            <option value="{{ $shop->id }}">{{ $shop->shop_name }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                <div class="form-group">
                                                                    <!-- Input field for item checksheet -->
                                                                    <div class="input-group mb-4" id="input-container">
                                                                        <input type="text" class="form-control" name="station[]" placeholder="Enter Station" required>
                                                                        <button type="button" id="add-input" class="btn btn-dark">+</button>
                                                                    </div>

                                                                    <script>
                                                                        $(document).ready(function() {
                                                                            $('#add-input').click(function() {
                                                                                $('#input-container').append('<div class="input-group mt-2 mb-2"><input type="text" class="form-control" name="station[]" placeholder="Enter Station" required><button type="button" class="btn btn-secondary remove-input">-</button></div>');
                                                                            });

                                                                            $(document).on('click', '.remove-input', function() {
                                                                                $(this).closest('.input-group').remove();
                                                                            });
                                                                        });
                                                                    </script>

                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Close</button>
                                                                <button type="submit" class="btn btn-primary">Submit</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            @if (session('status'))
                                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                                    <strong>{{ session('status') }}</strong>
                                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                                </div>
                                            @endif

                                            @if (session('failed'))
                                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                                    <strong>{{ session('failed') }}</strong>
                                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                                </div>
                                            @endif

                                            @if (count($errors) > 0)
                                                <div class="alert alert-info alert-dismissible fade show" role="alert">
                                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                                    <ul>
                                                        <li><strong>Data Process Failed !</strong></li>
                                                        @foreach ($errors->all() as $error)
                                                            <li><strong>{{ $error }}</strong></li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="table-responsive">
                                                <table id="tableUser" class="table table-bordered table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>No</th>
                                                            <th>Station Name</th>
                                                            <th>Shop Name</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @php
                                                            $no = 1;
                                                        @endphp
                                                        @foreach ($item as $data)
                                                            <tr>
                                                                <td>{{ $no++ }}</td>
                                                                <td>{{ $data->station_name }}</td>
                                                                <td>{{ $data->shop->shop_name }}</td>
                                                                <td>
                                                                    <button title="Edit Station" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modal-update{{ $data->id }}">
                                                                        <i class="fas fa-edit"></i>
                                                                    </button>
                                                                    {{-- <button title="Delete Station" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modal-delete{{ $data->id }}">
                                                                        <i class="fas fa-trash-alt"></i>
                                                                    </button> --}}
                                                                </td>
                                                            </tr>

                                                            {{-- Modal Update --}}
                                                            <div class="modal fade" id="modal-update{{ $data->id }}" tabindex="-1" aria-labelledby="modal-update{{ $data->id }}-label" aria-hidden="true">
                                                                <div class="modal-dialog">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h4 class="modal-title" id="modal-update{{ $data->id }}-label">Edit Station Name</h4>
                                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                        </div>
                                                                        <form action="{{ url('welding/station/update') }}" method="POST">
                                                                            @csrf
                                                                            @method('patch')
                                                                            <div class="modal-body">
                                                                                <input name="id" type="text" value="{{ $data->id }}" hidden>
                                                                                <div class="form-group mb-3">
                                                                                    <select name="shop_id" id="shop_id" class="form-control">
                                                                                        <option value="{{ $data->shop_id }}">{{ $data->shop->shop_name }}</option>
                                                                                        @foreach ($shopName as $shop)
                                                                                            <option value="{{ $shop->id }}">{{ $shop->shop_name }}</option>
                                                                                        @endforeach
                                                                                    </select>
                                                                                </div>
                                                                                <div class="form-group">
                                                                                    <input value="{{ $data->station_name }}" type="text" class="form-control" name="station" placeholder="Enter Station" required>
                                                                                </div>
                                                                            </div>
                                                                            <div class="modal-footer">
                                                                                <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Close</button>
                                                                                <button type="submit" class="btn btn-primary">Update</button>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            {{-- Modal Update --}}

                                                            {{-- Modal Delete --}}
                                                            <div class="modal fade" id="modal-delete{{ $data->id }}" tabindex="-1" aria-labelledby="modal-delete{{ $data->id }}-label" aria-hidden="true">
                                                                <div class="modal-dialog">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h4 class="modal-title" id="modal-delete{{ $data->id }}-label">Delete Station</h4>
                                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                        </div>
                                                                        <form action="{{ url('/dropdown/delete/'.$data->id) }}" method="POST">
                                                                            @csrf
                                                                            @method('delete')
                                                                            <div class="modal-body">
                                                                                <div class="form-group">
                                                                                    Are you sure you want to delete <label for="Dropdown">{{ $data->shop_name }}</label>?
                                                                                </div>
                                                                            </div>
                                                                            <div class="modal-footer">
                                                                                <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Close</button>
                                                                                <button type="submit" class="btn btn-primary">Submit</button>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            {{-- Modal Delete --}}
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.card-body -->
                            </div>
                            <!-- /.card -->
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->
                </div>
                <!-- /.container-fluid -->
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->
    </div>
</main>
<!-- For Datatables -->
<script>
    $(document).ready(function() {
        var table = $("#tableUser").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false
            // "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        });
    });
</script>
@endsection
