@extends('layouts.app')

@section('content')
    @include('include.flash-success')
    @include('include.flash-error')

    @if (count($stages) > 0)
        <div class="flex flex-col">
            <div class="overflow-x-auto">
                <div class="py-2 inline-block min-w-full">
                    <div class="overflow-hidden">
                        <table>
                            <thead class="bg-blue-300 border-b">
                                <tr>
                                    @include('include.table-th', ['text' => __('table.col_title')])
                                    @include('include.table-th', ['text' => __('table.col_slug')])
                                    @include('include.table-th', ['text' => ''])
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($stages as $stage)
                                    <tr class="bg-white border-b hover:bg-blue-100 transition">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $stage->name }}
                                        </td>
                                        <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                                            {{ $stage->slug }}
                                        </td>
                                        <td class="flex text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                                            @include('include.buttons.edit', ['link' => route('stages.edit', ['stage' => $stage->id])])
                                            @include('include.buttons.delete', ['link' => route('stages.destroy', ['stage' => $stage->id])])
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @else
        <p>@lang('empty.stages')</p>
    @endif

    <div class="py-4 mt-4">
        @include('include.buttons.create', ['link' => route('stages.create')])
    </div>
@endsection

