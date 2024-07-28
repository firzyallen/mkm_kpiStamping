@extends('layouts.master')

@section('content')
<main>
    <header class="page-header page-header-dark bg-gradient-primary-to-secondary pb-10">
        <div class="container-fluid px-4">
            <div class="page-header-content pt-4">
                <h1 class="page-header-title">
                    <div class="page-header-icon"><i data-feather="tool"></i></div>
                    Master Press Models
                </h1>
            </div>
        </div>
    </header>

    <!-- Main page content-->
    <div class="container-fluid px-4 mt-n10">
        <div class="content-wrapper">
            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Master Press Models</h3>
                                </div>

                                <!-- /.card-header -->
                                <div class="card-body">
                                    <div class="row">
                                        <div class="mb-3 col-sm-12">
                                            <button type="button" class="btn btn-dark btn-sm mb-2" data-bs-toggle="modal" data-bs-target="#modal-add">
                                                <i class="fas fa-plus-square"></i>
                                            </button>

                                            <!-- Modal -->
                                            <div class="modal fade" id="modal-add" tabindex="-1" aria-labelledby="modal-add-label" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="modal-add-label">Add Press Model</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <form action="{{ url('/masterpress/model/store') }}" method="POST">
                                                            @csrf
                                                            <div class="modal-body">
                                                                <div class="form-group mb-3">
                                                                    <select class="form-control" id="shop_id" name="shop_id" required>
                                                                        <option value="" disabled selected>Select Shop</option>
                                                                        @foreach ($shops as $shop)
                                                                            <option value="{{ $shop->id }}">{{ $shop->shop_name }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                <div id="input-container">
                                                                    <div class="input-group mt-2 mb-2">
                                                                        <input type="text" class="form-control" id="model_name" name="model[]" placeholder="Enter Model Name" required>
                                                                        <button type="button" class="btn btn-secondary" id="add-input">+</button>
                                                                    </div>
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

                                            <!-- Alerts -->
                                            @if (session('status'))
                                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                                <strong>{{ session('status') }}</strong>
                                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                            </div>
                                            @endif

                                            @if (session('error'))
                                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                                <strong>{{ session('error') }}</strong>
                                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
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
                                    <div class="table-responsive">
                                        <table id="tableModel" class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Model Name</th>
                                                    <th>Shop Name</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php $no = 1; @endphp
                                                @foreach ($models as $model)
                                                <tr>
                                                    <td>{{ $no++ }}</td>
                                                    <td>{{ $model->model_name }}</td>
                                                    <td>{{ $model->shop->shop_name }}</td>
                                                    <td>
                                                        <button title="Edit Model" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modal-update{{ $model->id }}">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                    </td>
                                                </tr>

                                                {{-- Modal Update --}}
                                                <div class="modal fade" id="modal-update{{ $model->id }}" tabindex="-1" aria-labelledby="modal-update{{ $model->id }}-label" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h4 class="modal-title" id="modal-update{{ $model->id }}-label">Edit Model Name</h4>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <form action="{{ url('/masterpress/model/update') }}" method="POST">
                                                                @csrf
                                                                @method('PUT')
                                                                <div class="modal-body">
                                                                    <input name="id" type="hidden" value="{{ $model->id }}">
                                                                    <div class="form-group mb-3">
                                                                        <input type="text" class="form-control" id="model_name" name="model_name" placeholder="Enter Model Name" value="{{ $model->model_name }}">
                                                                    </div>
                                                                    <div class="form-group mb-3">
                                                                        <select class="form-control" id="shop_id" name="shop_id" required>
                                                                            @foreach ($shops as $shop)
                                                                                <option value="{{ $shop->id }}" {{ $shop->id == $model->shop_id ? 'selected' : '' }}>{{ $shop->shop_name }}</option>
                                                                            @endforeach
                                                                        </select>
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
                                                {{-- End Modal Update --}}
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
        var table = $("#tableModel").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
        });

        // Add input field when the "+" button is clicked
        $('#add-input').click(function() {
            var newInputGroup = `
                <div class="input-group mt-2 mb-2">
                    <input type="text" class="form-control" name="model[]" placeholder="Enter Model Name" required>
                    <button type="button" class="btn btn-secondary remove-input">-</button>
                </div>`;
            $('#input-container').append(newInputGroup);
        });

        // Remove input field when the "-" button is clicked
        $(document).on('click', '.remove-input', function() {
            $(this).closest('.input-group').remove();
        });
    });
</script>
@endsection
