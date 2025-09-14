<div>
    <div class="card mb-3" style="background: rgba(255, 255, 255, 0.5);">
        <div class="card-body">
            <div class="my-3">
                <input type="text" class="form-control text-center p-3" autofocus wire:model.live.debounce.500ms="contract_number" placeholder="{{__('message.contract_number')}}" />
            </div>

            <div
                wire:loading.block
                wire:target="contract_number"
                style="display: none;"
            >
                <div class="placeholder-wave">
                    <span class="placeholder col-12 mb-2"></span>
                </div>
            </div>

            <div wire:loading.remove wire:target="contract_number">
                @if($applicant)
                    <div class="alert alert-info text-white fade-in">
                        <div>نام متقاضی: {{ $applicant_full_name }}</div>
                        <div>ایمیل: {{ $applicant_email }}</div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="card mb-3" style="background: rgba(255, 255, 255, 0.5);">
        <div class="card-body">
            <div
                x-data="{ isOver:false }"
                x-on:dragover.prevent="isOver=true"
                x-on:dragleave.prevent="isOver=false"
                x-on:drop.prevent="isOver=false"
                class="border border-2 rounded p-4 text-center"
                :class="{ 'border-primary bg-light': isOver }"
            >
                <input type="file" multiple class="form-control mb-2" wire:model="files" />
                <div>فایل‌ها را اینجا رها کنید یا انتخاب کنید</div>
            </div>

            @if(count($required_documents))
                <div class="mt-3 d-flex text-sm">
                    مدارک مورد نیاز:
                    @foreach($required_documents as $doc)
                        @php
                            $has = collect($uploaded_files)->firstWhere('type', $doc);
                        @endphp
                        <div class="mx-1">
                            {{ $doc }}
                            {!! $has ? '<i class="fa-solid fa-circle-check text-success"></i>' : '<i class="fa-solid fa-circle text-secondary"></i>' !!}
                        </div>
                    @endforeach
                </div>
            @endif

            @if(count($uploaded_files))
                <div class="mt-3">
                    <div class="fw-bold mb-2">فایل‌های آپلود شده</div>
                    <ul class="list-group">
                        @foreach($uploaded_files as $file)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>{{ $file['name'] }}</span>
                                <span class="badge bg-info">{{ $file['type'] }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>

    <div class="text-start">
        <button class="btn btn-primary" @disabled(!$application_case_id)>{{__('message.continue')}} <i class="fa-solid fa-chevron-left"></i></button>
    </div>
</div>


