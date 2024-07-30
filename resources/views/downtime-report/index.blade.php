@extends('layouts.master')

@section('content')
    <main>
        <header class="page-header page-header-dark bg-gradient-primary-to-secondary pb-10">
            <div class="container-fluid px-4">
                <div class="page-header-content pt-4">
                    <h1 class="page-header-title">
                        <div class="page-header-icon"><i data-feather="tool"></i></div>
                        Downtime Reports
                    </h1>
                </div>
            </div>
        </header>
        <!-- Main page content-->
        <div class="container-fluid px-4 mt-n10">
            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                </section>

                <!-- Main content -->
                <section class="content">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Downtime Reports</h3>
                                    </div>
                                    <!-- /.card-header -->
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="mb-3 col-sm-12">
                                                <button type="button" class="btn btn-dark btn-sm mb-2"
                                                    data-bs-toggle="modal" data-bs-target="#modal-add">
                                                    <i class="fas fa-plus-square"></i> Add Downtime Report
                                                </button>

                                                <!-- Modal -->
                                                <div class="modal fade" id="modal-add" tabindex="-1"
                                                    aria-labelledby="modal-add-label" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="modal-add-label">Add Downtime
                                                                    Report</h5>
                                                                <button type="button" class="btn-close"
                                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <form action="{{ url('/downtime-report/store-header') }}"
                                                                method="POST">
                                                                @csrf
                                                                <div class="modal-body">
                                                                    <div class="form-group mb-3">
                                                                        <label for="section_id">Section</label>
                                                                        <select name="section_id" id="section_id"
                                                                            class="form-control" required>
                                                                            <option value="">- Please Select Section -
                                                                            </option>
                                                                            @foreach ($sectionTypes as $section)
                                                                                <option value="{{ $section->id }}">
                                                                                    {{ ucfirst($section->section_name) }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                    <div class="form-group mb-3">
                                                                        <label for="shift">Shift</label>
                                                                        <select name="shift" id="shift"
                                                                            class="form-control" required>
                                                                            <option value="">- Please Select Shift -
                                                                            </option>
                                                                            @foreach ($shifts as $shift)
                                                                                <option value="{{ $shift }}">
                                                                                    {{ $shift }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                    <div class="form-group mb-3">
                                                                        <label for="date">Date</label>
                                                                        <input type="date" class="form-control"
                                                                            id="date" name="date" required>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-dark"
                                                                        data-bs-dismiss="modal">Close</button>
                                                                    <button type="submit"
                                                                        class="btn btn-primary">Submit</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-12">
                                                <!--alert success -->
                                                @if (session('status'))
                                                    <div class="alert alert-success alert-dismissible fade show"
                                                        role="alert">
                                                        <strong>{{ session('status') }}</strong>
                                                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                            aria-label="Close"></button>
                                                    </div>
                                                @endif

                                                @if (session('failed'))
                                                    <div class="alert alert-danger alert-dismissible fade show"
                                                        role="alert">
                                                        <strong>{{ session('failed') }}</strong>
                                                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                            aria-label="Close"></button>
                                                    </div>
                                                @endif

                                                <!--validasi form-->
                                                @if (count($errors) > 0)
                                                    <div class="alert alert-info alert-dismissible fade show"
                                                        role="alert">
                                                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                            aria-label="Close"></button>
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
                                            <table id="tableDowntime" class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Section</th>
                                                        <th>Date</th>
                                                        <th>Shift</th>
                                                        <th>Reporter</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php
                                                        $no = 1;
                                                    @endphp
                                                    @foreach ($headers as $header)
                                                        <tr>
                                                            <td>{{ $no++ }}</td>
                                                            <td>{{ ucfirst($header->section->section_name) }}</td>
                                                            <td>{{ $header->date }}</td>
                                                            <td>{{ $header->shift }}</td>
                                                            <td>{{ $header->created_by }}</td>
                                                            <td>
                                                                <a title="Detail Downtime Report"
                                                                    href="{{ url('/downtime-report/show/' . encrypt($header->id)) }}"
                                                                    class="btn btn-info btn-sm me-2"><i
                                                                        class="fas fa-info"></i></a>
                                                                <a title="Edit Downtime Report"
                                                                    href="{{ url('/downtime-report/update/' . encrypt($header->id)) }}"
                                                                    class="btn btn-primary btn-sm me-2"><i
                                                                        class="fas fa-edit"></i></a>
                                                                <form
                                                                    action="{{ url('/downtime-report/delete/' . encrypt($header->id)) }}"
                                                                    method="POST" class="d-inline"
                                                                    onsubmit="return confirm('Are you sure you want to delete this report?');">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit"
                                                                        class="btn btn-danger btn-sm"><i
                                                                            class="fas fa-trash"></i></button>
                                                                </form>
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
            var table = $("#tableDowntime").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "order": [
                    [2, "desc"]
                ] // Sort by date (3rd column, zero-based index) in descending order
            });
        });
    </script>
@endsection
