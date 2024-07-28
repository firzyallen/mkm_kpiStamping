@extends('layouts.master')

@section('content')
<main>
    <header class="page-header page-header-dark bg-gradient-primary-to-secondary pb-10">
        <div class="container-fluid px-4">
            <div class="page-header-content pt-4">
            </div>
        </div>
    </header>
    <!-- Main page content-->
    <div class="container-fluid px-4 mt-n10">
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Factory B Daily Reports</h3>
                                </div>
                                <!-- Export Modal -->
                                <div class="modal fade" id="modal-export" tabindex="-1" aria-labelledby="modal-export-label" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="modal-export-label">Export to Excel</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form action="{{ url('/daily-report/factoryb/export') }}" method="POST">
                                                @csrf
                                                <div class="modal-body">
                                                    <div class="form-group mb-3">
                                                        <label for="export-month">Select Month</label>
                                                        <input type="month" class="form-control" id="export-month" name="month" required>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary">Export</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Export Modal -->
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <div class="row">
                                        <div class="mb-3 col-sm-12">
                                            <button type="button" class="btn btn-dark btn-sm mb-2" data-bs-toggle="modal" data-bs-target="#modal-add">
                                                <i class="fas fa-plus-square"></i>
                                            </button>
                                            <button type="button" class="btn btn-success btn-sm mb-2" data-bs-toggle="modal" data-bs-target="#modal-export">
                                                <i class="fas fa-file-excel"></i> Export to Excel
                                            </button>

                                            <!-- Modal -->
                                            <div class="modal fade" id="modal-add" tabindex="-1" aria-labelledby="modal-add-label" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="modal-add-label">Add Daily Report</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <form action="{{ url('/daily-report/factoryb/store/main') }}" method="POST">
                                                            @csrf
                                                            <div class="modal-body">
                                                                <div class="form-group mb-3">
                                                                    <select name="shift" id="shift" class="form-control" required>
                                                                        <option value="">- Please Select Shift -</option>
                                                                        @foreach ($categories as $shift)
                                                                            <option value="{{ $shift->name_value }}">{{ $shift->name_value }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                <div class="form-group mb-3">
                                                                    <input type="date" name="date" class="form-control" required>
                                                                </div>
                                                                <div class="form-group mb-3">
                                                                    <input type="text" class="form-control" id="pic" name="pic" placeholder="PIC" required>
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

                                        <div class="col-sm-12">
                                            <!--alert success -->
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

                                            <!--validasi form-->
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
                                            <!--end validasi form-->
                                        </div>
                                    </div>
                                    <div class="table-responsive">
                                        <table id="tableUser" class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Date</th>
                                                    <th>Shift</th>
                                                    <th>Created By</th>
                                                    <th>PIC</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $no = 1;
                                                @endphp
                                                @foreach ($items as $data)
                                                    <tr>
                                                        <td>{{ $no++ }}</td>
                                                        <td>{{ $data->date }}</td>
                                                        <td>{{ $data->shift }}</td>
                                                        <td>{{ $data->created_by }}</td>
                                                        <td>{{ $data->pic }}</td>

                                                        <td>
                                                            <a title="Detail Report" href="{{url('/daily-report/factoryb/detail/'.encrypt($data->id))}}" class="btn btn-info btn-sm me-2"><i class="fas fa-info"></i></a>
                                                            <a title="Edit Report" href="{{url('/daily-report/factoryb/update/'.encrypt($data->id))}}" class="btn btn-primary btn-sm me-2"><i class="fas fa-edit"></i></a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
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
            "autoWidth": false,
        });
    });
</script>
@endsection
