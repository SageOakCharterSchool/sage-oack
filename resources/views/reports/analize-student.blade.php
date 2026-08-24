@extends('layouts.admin')

@section('content')
    <div class="col-12 mb-2">
        <a class="btn btn-warning float-end" href="/admin/consolidate-view">Back</a>
    </div>
    <div class="clearfix"></div>
    <div class="row mt-2">
        <div class="accordion" id="accordionExample0">
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseConsolidated" aria-expanded="true" aria-controls="collapseConsolidated">
                        Consolidated Info
                    </button>
                </h2>
                <div id="collapseConsolidated" class="accordion-collapse collapse " data-bs-parent="#collapseConsolidated">
                    <div class="accordion-body">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    Consolidated Info
                                </div>
                                <div class="card-body">


                                    <table class="table table-responsive">
                                        <thead>
                                            <tr>
                                                <th scope="col" style="width:10%">Column</td>
                                                <th scope="col" style="width:10%">Description</td>
                                                <th scope="col">Table Source</td>
                                                <th scope="col">Formula Source</td>
                                                <th scope="col">Formula</td>
                                                <th scope="col">Value</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($mappingFfields as $field)
                                                <tr>
                                                    <td>
                                                        {{ $field->column_name }}
                                                    </td>
                                                    <td>
                                                        {{ $field->column_description }}
                                                    </td>

                                                    <td>{{ \App\Models\ConsolidateMapping::getFieldSource($field->field_source) ?? '' }}
                                                    </td>

                                                    <td><a
                                                            href="/admin/formulas/{{ $field->formula_id }}">{{ \App\Models\Formula::getFormulaName($field->formula_id)->formula_name ?? '' }}</a><br>
                                                            {{(isset($formulas[$field->formula_id])) ? html_entity_decode($formulas[$field->formula_id]->formula) : ''}}
                                                    </td>
                                                    <td>
                                                        {{ $consolidatedRow[$field->column_name] }}
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
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#dataUploaded" aria-expanded="false" aria-controls="dataUploaded">
                        Data Uploaded
                    </button>
                </h2>
                <div id="dataUploaded" class="accordion-collapse collapse" data-bs-parent="#dataUploaded">
                    <div class="accordion-body">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    Data Uploaded
                                </div>
                                <div class="card-body">
                                    <table class="table table-responsive">
                                        <thead>
                                            <tr>
                                                <th scope="col" style="width:10%">Table Id</td>
                                                <th scope="col">Table</td>
                                            </tr>
                                        </thead>
                                        <tbody>



                                            @foreach ($tables as $table)
                                                <tr>
                                                    <td style="vertical-align: top;">{{ $table->id }}</td>
                                                    <td>
                                                        <div class="accordion" id="accordionExample">
                                                            <div class="accordion-item">
                                                                <h2 class="accordion-header">
                                                                    <button class="accordion-button" type="button"
                                                                        data-bs-toggle="collapse"
                                                                        data-bs-target="#collapse_{{ $table->id }}"
                                                                        aria-expanded="true"
                                                                        aria-controls="collapse_{{ $table->id }}">
                                                                        {{ $table->table_name }} Records:
                                                                        {{ count($records[$table->id] ?? 0) }}
                                                                    </button>
                                                                </h2>
                                                                <div id="collapse_{{ $table->id }}"
                                                                    class="accordion-collapse collapse "
                                                                    data-bs-parent="#accordionExample">
                                                                    <div class="accordion-body">

                                                                        <table>
                                                                            <tr>
                                                                                <th>row</th>
                                                                                <th>Column</th>
                                                                                <th>Field</th>
                                                                                <th>Value</th>
                                                                            </tr>
                                                                            @foreach ($records[$table->id] as $k => $tableInfo)
                                                                                <tr>
                                                                                    <td>&nbsp;</td>
                                                                                    @php
                                                                                        //dd($tableFields[$table->id])
                                                                                    @endphp
                                                                                    <td>{{ $tableInfo->column }}</td>
                                                                                    <td>{{ $tableFields[$table->id][$tableInfo->column] }}
                                                                                    </td>
                                                                                    <td>{{ $tableInfo->field_value }}</td>
                                                                                </tr>
                                                                            @endforeach
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </div>

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
        </div>
    @endsection
