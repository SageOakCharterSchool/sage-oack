@extends('layouts.user')
@section('content')

    <h4>Reports Repository</h4>
    <table>
        <tr>
            <td style="width: 85%">
                View Reports
            </td>
            <td class="text-center">
                <div class="input-group mb-3">
                    @if (Auth::user()->isAdmin())
                    <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">View Reports</button>
                    <ul class="dropdown-menu">
                      <li><a class="dropdown-item" href="/admin/generate-consolidated-report">Current Cycle</a></li>
                      @foreach ($cycles as $cycle )

                      <li><a class="dropdown-item" href="/admin/generate-consolidated-report/{{$cycle->id}}">{{$cycle->cycle_name}}</a></li>
                      @endforeach
                    </ul>
                    @else
                    <td class="text-center">
                        <a href="/admin/generate-consolidated-report" class="btn btn-primary btn-sm text-center">View Reports</a>
                    </td>
                    @endif
                  </div>

            </td>
        </tr>
    </table>

@endsection
