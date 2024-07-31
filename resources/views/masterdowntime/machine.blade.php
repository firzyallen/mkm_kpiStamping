@extends('layouts.master')

@section('content')
    <main>
        <header class="page-header page-header-dark bg-gradient-primary-to-secondary pb-10">
            <div class="container-fluid px-4">
                <div class="page-header-content pt-4">
                    <h1 class="page-header-title">
                        <div class="page-header-icon"><i data-feather="tool"></i></div>
                        Downtime Machines
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
                                        <h3 class="card-title">Downtime Machines</h3>
                                    </div>
                                    <!-- /.card-header -->
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="mb-3 col-sm-12">
                                                <button type="button" class="btn btn-dark btn-sm mb-2"
                                                    data-bs-toggle="modal" data-bs-target="#modal-add">
                                                    <i class="fas fa-plus-square"></i> Add Machine
                                                </button>
                                                <!-- Modal -->
                                                <div class="modal fade" id="modal-add" tabindex="-1"
                                                    aria-labelledby="modal-add-label" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="modal-add-label">Add Machine
                                                                </h5>
                                                                <button type="button" class="btn-close"
                                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <form action="{{ url('/masterdowntime/store') }}"
                                                                method="POST">
                                                                @csrf
                                                                <div class="modal-body">
                                                                    <div class="form-group mb-3">
                                                                        <label for="shop_id">Shop</label>
                                                                        <select name="shop_id" id="shop_id"
                                                                            class="form-control" required>
                                                                            <option value="">- Please Select Shop -
                                                                            </option>
                                                                            @foreach ($shops as $shop)
                                                                                <option value="{{ $shop->id }}"
                                                                                    data-section="{{ $shop->section->section_name }}">
                                                                                    {{ $shop->shop_name }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                    <div class="form-group mb-3">
                                                                        <label for="section">Section</label>
                                                                        <input type="text" class="form-control"
                                                                            id="section" name="section" readonly>
                                                                    </div>
                                                                    <div class="form-group mb-3">
                                                                        <label for="machine_name">Enter Machine
                                                                            Names</label>
                                                                        <div id="input-container">
                                                                            <div class="input-group mt-2 mb-2">
                                                                                <input type="text" class="form-control"
                                                                                    name="machine[]"
                                                                                    placeholder="Enter Machine Name"
                                                                                    required>
                                                                                <button type="button"
                                                                                    class="btn btn-secondary"
                                                                                    id="add-input">+</button>
                                                                            </div>
                                                                        </div>
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
                                        </div>
                                        <div class="table-responsive">
                                            <table id="tableMachines" class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Machine Name</th>
                                                        <th>Shop Name</th>
                                                        <th>Section</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php $no = 1; @endphp
                                                    @foreach ($machines as $machine)
                                                        <tr>
                                                            <td>{{ $no++ }}</td>
                                                            <td>{{ $machine->machine_name }}</td>
                                                            <td>{{ $machine->shop->shop_name }}</td>
                                                            <td>{{ $machine->shop->section->section_name }}</td>
                                                            <td>
                                                                <button title="Edit Machine" class="btn btn-primary btn-sm"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#modal-update{{ $machine->id }}">
                                                                    <i class="fas fa-edit"></i>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                        <!-- Modal Update -->
                                                        <div class="modal fade" id="modal-update{{ $machine->id }}"
                                                            tabindex="-1"
                                                            aria-labelledby="modal-update{{ $machine->id }}-label"
                                                            aria-hidden="true">
                                                            <div class="modal-dialog">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h4 class="modal-title"
                                                                            id="modal-update{{ $machine->id }}-label">Edit
                                                                            Machine Name</h4>
                                                                        <button type="button" class="btn-close"
                                                                            data-bs-dismiss="modal"
                                                                            aria-label="Close"></button>
                                                                    </div>
                                                                    <form action="{{ url('/masterdowntime/update') }}"
                                                                        method="POST">
                                                                        @csrf
                                                                        <input type="hidden" name="_method" value="PATCH">
                                                                        <div class="modal-body">
                                                                            <input name="id" type="hidden"
                                                                                value="{{ $machine->id }}">
                                                                            <div class="form-group mb-3">
                                                                                <label for="machine_name">Machine
                                                                                    Name</label>
                                                                                <input type="text" class="form-control"
                                                                                    id="machine_name" name="machine_name"
                                                                                    value="{{ $machine->machine_name }}"
                                                                                    required>
                                                                            </div>
                                                                            <div class="form-group mb-3">
                                                                                <label for="shop_id">Shop</label>
                                                                                <select name="shop_id"
                                                                                    id="shop_id_{{ $machine->id }}"
                                                                                    class="form-control" required>
                                                                                    @foreach ($shops as $shop)
                                                                                        <option
                                                                                            value="{{ $shop->id }}"
                                                                                            data-section="{{ $shop->section->section_name }}"
                                                                                            {{ $shop->id == $machine->shop_id ? 'selected' : '' }}>
                                                                                            {{ $shop->shop_name }}</option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div>
                                                                            <div class="form-group mb-3">
                                                                                <label for="section">Section</label>
                                                                                <input type="text" class="form-control"
                                                                                    id="section_{{ $machine->id }}"
                                                                                    name="section"
                                                                                    value="{{ $machine->shop->section->section_name }}"
                                                                                    readonly>
                                                                            </div>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="button" class="btn btn-dark"
                                                                                data-bs-dismiss="modal">Close</button>
                                                                            <button type="submit"
                                                                                class="btn btn-primary">Update</button>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- End Modal Update -->
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
            var table = $("#tableMachines").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
            });

            // Add input field when the "+" button is clicked
            $('#add-input').click(function() {
                var newInputGroup = `
                <div class="input-group mt-2 mb-2">
                    <input type="text" class="form-control" name="machine[]" placeholder="Enter Machine Name" required>
                    <button type="button" class="btn btn-secondary remove-input">-</button>
                </div>`;
                $('#input-container').append(newInputGroup);
            });

            // Remove input field when the "-" button is clicked
            $(document).on('click', '.remove-input', function() {
                $(this).closest('.input-group').remove();
            });

            // Update section when shop is selected
            $('#shop_id').change(function() {
                var selectedShop = $(this).find('option:selected');
                var section = selectedShop.data('section');
                $('#section').val(section);
            });

            // Update section when shop is selected in the update modal
            @foreach ($machines as $machine)
                $('#shop_id_{{ $machine->id }}').change(function() {
                    var selectedShop = $(this).find('option:selected');
                    var section = selectedShop.data('section');
                    $('#section_{{ $machine->id }}').val(section);
                });
            @endforeach
        });
    </script>
@endsection
