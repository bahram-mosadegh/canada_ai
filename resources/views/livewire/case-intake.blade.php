<div class="row justify-content-center mb-4">
    <div class="col-md-6 mb-4">
        <div class="card mb-4" style="background: rgba(255, 255, 255, 0.6);">
            <div class="card-body">
                <div class="my-3">
                    <input type="text" class="form-control text-center p-3 {{$contract_number && !$contract_lookup_successful ? 'is-invalid' : ''}}" autofocus wire:model.live.debounce.500ms="contract_number" placeholder="{{__('message.contract_number')}}" @disabled($application_case_id) />
                </div>
            </div>
        </div>

        <div class="card" style="background: rgba(255, 255, 255, 0.6);">
            <div class="card-body">
                <input id="files" type="file" multiple class="form-control mb-2" wire:model="files" style="display: none;" />
                <div
                    x-data="{
                        isOver: false,
                        progress: 0,
                        handleDrop(e) {
                            this.isOver = false;
                            const dropped = e.dataTransfer?.files;
                            if (!dropped || dropped.length === 0 || !$wire.client_raw) return;

                            $wire.uploadMultiple('files', dropped,
                                () => {}, // success
                                () => {}, // error
                                (event) => { this.progress = event.detail.progress; } // progress
                            );
                        },
                    }"
                    x-on:dragover.prevent="isOver=true"
                    x-on:dragleave.prevent="isOver=false"
                    x-on:drop.prevent="handleDrop($event)"
                    class="border border-2 border-radius-lg border-white p-4 text-center {{$case_raw ? 'cursor-pointer' : ''}}"
                    :class="{ 'bg-light': isOver && $wire.client_raw }"
                    onClick="{{$case_raw ? "$('#files').click()" : ''}}"
                >
                    <div class="text-secondary text-sm">فایل‌ها را اینجا رها کنید یا انتخاب کنید</div>
                </div>

                <div
                    wire:loading.block
                    wire:target="contract_number"
                    style="display: none;"
                    class="placeholder-wave mt-4"
                >
                </div>
                <div wire:loading.remove wire:target="contract_number">
                    @if(count($required_documents))
                        <div class="mt-4 fade-in d-flex text-sm">
                            <p class="text-sm mb-2">مدارک مورد نیاز:</p>
                            @foreach($required_documents as $doc)
                                @php
                                    $has = collect($uploaded_files)->firstWhere('type', $doc);
                                @endphp
                                <div class="me-3">
                                    {{ __('message.'.$doc.'_file') }}
                                    {!! $has ? '<i class="fa-solid fa-circle-check text-success"></i>' : '<i class="fa-solid fa-circle text-secondary"></i>' !!}
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
                

                @if(count($uploaded_files))
                    <hr class="my-4 text-white opacity-10 fade-in">
                    <div class="fade-in">
                        <p class="text-sm">مدارک آپلود شده:</p>
                        <ul class="list-group p-0 border-radius-lg">
                            @foreach($uploaded_files as $file)
                                <li class="list-group-item d-flex justify-content-between align-items-center p-2 fade-in">
                                    <span class="text-xs">{{ $file['name'] }}</span>
                                    <div class="d-flex">
                                        <div style="min-width: 50px;">
                                            @if($file['status'] == 'analysing')
                                            <img src="{{asset('assets/img/ai-loading.gif')}}" width="20px" title="{{__('message.analysing')}}">
                                            @else
                                            <i class="fa-solid fa-circle text-xs text-{{$file['status'] == 'analysed' ? 'success' : 'secondary'}}" title="{{__('message.'.$file['status'])}}"></i>
                                            @endif
                                        </div>
                                        <div style="min-width: 100px;">
                                            @if($file['type'])
                                            <span class="badge bg-info w-100" title="{{__('message.file_type')}}">{{ $file['type'] }}</span>
                                            @else
                                            <div class="placeholder-wave" title="{{__('message.file_type')}}"></div>
                                            @endif
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    @if(collect($uploaded_files)->where('status', '<>', 'analysed')->count())
                    <div wire:poll.1000ms="checkFileStatus"></div>
                    @endif
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-4">
        <div class="card mb-3 h-100" style="background: rgba(255, 255, 255, 0.6);">
            <div class="card-body">
                <p class="text-sm">اطلاعات پرونده:</p>
                <div
                    wire:loading.block
                    wire:target="contract_number"
                    style="display: none;"
                >
                    <div class="table-responsive">
                        <table class="table align-items-center mb-0">
                            <tbody>
                                <tr>
                                    <td class="placeholder-wave"></td>
                                </tr>
                                <tr>
                                    <td class="placeholder-wave"></td>
                                </tr>
                                <tr>
                                    <td class="placeholder-wave"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div wire:loading.remove wire:target="contract_number">
                    @if($case_raw)
                        <div class="table-responsive">
                            <table class="table align-items-center mb-0">
                                <tbody>
                                    <tr class="fade-in">
                                        <td class="text-xs opacity-8 text-bold">نام و نام خانودگی کلاینت</td>
                                        <td>
                                            <div class="text-xs text-bold text-start">{{ $case_raw['client_full_name'] }}</div>
                                        </td>
                                    </tr>
                                    <tr class="fade-in">
                                        <td class="text-xs opacity-8 text-bold">موبایل کلاینت</td>
                                        <td>
                                            <div class="text-xs text-bold text-start">{{ $case_raw['client_mobile'] }}</div>
                                        </td>
                                    </tr>
                                    @foreach($case_raw['applicant_names'] as $applicant_name)
                                    <tr class="fade-in">
                                        <td class="text-xs opacity-8 text-bold" width="25%">نام و نام خانوادگی مسافر{{ $loop->count > 1 ? ' '.($loop->index + 1) : '' }}</td>
                                        <td>
                                            <div class="text-xs text-bold text-start">{{ $applicant_name }}</div>
                                        </td>
                                    </tr>
                                    @endforeach
                                    <tr class="fade-in">
                                        <td class="text-xs opacity-8 text-bold">نوع پرونده</td>
                                        <td>
                                            <div class="text-xs text-bold text-start">{{ __('message.'.$case_raw['type_of_visa']) }}</div>
                                        </td>
                                    </tr>
                                    <tr class="fade-in">
                                        <td class="text-xs opacity-8 text-bold">دارای بیزینس</td>
                                        <td>
                                            <div class="text-xs text-bold text-start">{{ $case_raw['is_business'] ? __('message.yes') : __('message.no') }}</div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-10">
        <div class="text-start">
            <button class="btn btn-primary" @disabled(!$application_case_id)>{{__('message.continue')}} <i class="fa-solid fa-chevron-left"></i></button>
        </div>
    </div>
</div>


