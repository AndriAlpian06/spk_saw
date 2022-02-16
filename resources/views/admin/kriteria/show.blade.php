@extends('layouts.app')
@section('title', 'SPK SAW | '.$kriteria->nama_kriteria)
@section('css')
    <!-- Custom styles for this page -->
    <link href="{{ asset('vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@stop
@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="card shadow mb-4">
                <!-- Card Header - Accordion -->
                <a href="#tambahcrips" class="d-block card-header py-3" data-toggle="collapse"
                    role="button" aria-expanded="true" aria-controls="collapseCardExample">
                    <h6 class="m-0 font-weight-bold text-primary">Tambah Crips</h6>
                </a>
                <!-- Card Content - Collapse -->
                <div class="collapse show" id="tambahcrips">
                    <div class="card-body">
                        @if(Session::has('msg'))
                            <div class="alert alert-info alert-dismissible fade show" role="alert">
                                <strong>Info!</strong> {{ Session::get('msg') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif
                        <form action="{{ route('crips.store') }}" method="post">
                            @csrf
                            <input type="hidden" value="{{ $kriteria->id }}" name="kriteria_id">
                            <div class="form-group">
                                <label for="nama">Nama Crips</label>
                                <input type="text" class="form-control @error ('nama_crips') is-invalid @enderror"
                                name="nama_crips" value="{{ old('nama_crips') }}">

                                @error('nama_crips')
                                    <div class="invalid-feedback" role="alert">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="bobot">Bobot Crips</label>
                                <input type="text" class="form-control @error ('bobot') is-invalid @enderror"
                                name="bobot" value="{{ old('bobot') }}">

                                @error('bobot')
                                    <div class="invalid-feedback" role="alert">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <button class="btn btn-sm btn-primary">Simpan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card shadow mb-4">
                <!-- Card Header - Accordion -->
                <a href="#listcrips" class="d-block card-header py-3" data-toggle="collapse"
                    role="button" aria-expanded="true" aria-controls="collapseCardExample">
                    <h6 class="m-0 font-weight-bold text-primary">List Crips</h6>
                </a>
                <!-- Card Content - Collapse -->
                <div class="collapse show" id="listcrips">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="DataTable">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Crips</th>
                                        <th>Bobot</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $no = 1; @endphp
                                    @foreach($crips as $row)
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td>{{ $row->nama_crips }}</td>
                                            <td>{{ $row->bobot }}</td>
                                            <td>
                                                <a href="{{ route('crips.edit',$row->id) }}"
                                                class="btn btn-sm btn-cirle btn-warning"><i class="fa fa-edit"></i>
                                                </a>
                                                <a href="{{ route('crips.destroy',$row->id) }}"
                                                class="btn btn-sm btn-cirle btn-danger hapus"><i class="fa fa-trash"></i>
                                                </a>
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
    </div>
@stop
@section('js')
    <!-- Page level plugins -->
    <script src="{{ asset('vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('js/sweetalert.js') }}"></script>
    <script>
        $(document).ready(function(){
            $('#DataTable').DataTable();

            $('.hapus').on('click', function(){
                swal({
                    title: "Apa kamu yakin?",
                    text: "Sekali kamu hapus, data tidak dapat dikembalikan",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                    })
                    .then((willDelete) => {
                    if (willDelete) {
                        $.ajax({
                            url: $(this).attr('href'),
                            type: 'DELETE',
                            data: {
                                '_token': "{{ csrf_token() }}"
                            },
                            success: function()
                            {
                                swal("Data Berhasil dihapus", {
                                icon: "success",
                                }).then((willDelete) => {
                                    window.location = "{{ route('kriteria.show',$kriteria->id) }}"
                                });
                            }
                        })
                        
                    } else {
                        swal("Data aman!");
                    }
                });

                return false;
            })
        })
    </script>
@stop