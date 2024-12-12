<ul class="list custom-scrollbar list-group m-0 p-0" style="padding: 0 !important">
    @if ($data->isEmpty())
        <p>لا توجد مراسلات</p>
    @else
        @php
            $text = [];
            $maxLengthName = 40;
        @endphp
        @foreach ($data as $key)
            <li onclick="list_message_ajax({{ $key->c_id }})" style="cursor: pointer"
                class="clearfix list-group-item mt-2">
                <div style="" class=" d-flex">
                    <div class="d-block" style="flex: 2">
                        <p style="font-size: 11px;"><span class=""></span>
                            <span>
                                {{ strlen($key->user->name) > $maxLengthName ? mb_substr($key->user->name, 0, $maxLengthName) . '...' : $key->user->name }}
                            </span>
                        </p>
                    </div>
                    <div class="d-block text-end" style="flex: 1">
                        <p style="font-size: 9px;" class="text-end text-bold">
                            {{ \Carbon\Carbon::parse($key->created_at)->diffForHumans() }}</p>
                    </div>
                </div>
                <div class="d-block">
                    @php
                        $text = [];
                    @endphp
                    <p class="p-0 m-0" style="font-size: 11px; font-weight: bold"><span class=""></span> <span>
                            @foreach ($key->participants as $users)
                                @php
                                    $maxLength = 50;
                                @endphp
                                @foreach (json_decode($users->uc_user_id) as $user)
                                    @php
                                        $text[] = \App\Models\User::find($user)->name ?? 'لا يوجد اسم';
                                    @endphp
                                @endforeach
                            @endforeach

                            @php
                                // Convert array to string with a separator (e.g., ", ")
                                $textString = implode(', ', $text);
                            @endphp

                            <span style="font-size: 8px" class="">
                                {{ strlen($textString) > $maxLength ? mb_substr($textString, 0, $maxLength) . '...' : $textString }}
                            </span>
                        </span>
                    </p>

                    <p class="p-0 m-0 text-primary" style="font-size: 11px;font-weight: bold">
                            {{ strlen($key->c_name) > $maxLengthName ? mb_substr($key->c_name, 0, $maxLengthName) . '...' : $key->c_name }}
                    </p>

                    <p class="p-0 m-0" style="font-size: 11px;">
                        {{ strlen($key->getLastMessage()->m_message_text) > $maxLengthName ? mb_substr($key->getLastMessage()->m_message_text, 0, $maxLengthName) . '...' : $key->getLastMessage()->m_message_text }}
                    </p>
                </div>
            </li>
        @endforeach
    @endif

</ul>
