@extends('layouts.app')

@section('subtitle', 'Dashboard')
@section('content_header_title', 'Dashboard')

@section('content_body')
    <div class="container-fluid">
        <div class="form-group">
            <select id="asbSelect" class="form-control select2bs4" style="width: 100%;" name="asb_kode">
            </select>
        </div>
    </div>
@stop

@push('css')
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@1.5.2/dist/select2-bootstrap4.min.css">
@endpush

@push('js')
    <script>
        $(function() {
            $('#asbSelect').select2({
                theme: 'bootstrap4',
                placeholder: '-- Pilih ASB --',
                allowClear: true,
                ajax: {
                    url: '{{ route('asb.options') }}',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            q: params.term
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data
                        };
                    },
                    cache: true
                }
            });
        });
    </script>
@endpush
