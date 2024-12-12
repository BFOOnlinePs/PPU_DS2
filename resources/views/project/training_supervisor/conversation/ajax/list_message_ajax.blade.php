    <div class="card">
        <div class="p-0 card-body">
            <div class="chat-box row">
                <div class="chat-right-aside col">
                    <div class="chat">
                        <div class="media chat-header clearfix">
                            <div class="media-body">
                                <div class="about">
                                    <div class="">من : <span>{{ $conversations->user->name }}</span></div>
                                    الى :
                                    @foreach ($conversations->participants as $users)
                                        @foreach (json_decode($users->uc_user_id) as $user)
                                            <span class="badge badge-info mt-2">
                                                {{ \App\Models\User::find($user)->name ?? 'لا يوجد اسم' }}
                                            </span>
                                        @endforeach
                                    @endforeach
                                    <div class=" mt-2">
                                        <span>العنوان : {{ $conversations->c_name }}</span>
                                    </div>
                                    {{-- <div class="status digits">Last seen today 15:24</div> --}}
                                </div>
                            </div>
                            <ul
                                class="simple-list list-inline float-start float-sm-end chat-menu-icons flex-row list-group">
                                {{-- <li class="list-inline-item list-group-item" onclick="openFileDialog()">
                                    <a href="javascript:void(0)" title="Select File">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                            <polyline points="14 2 14 8 20 8"></polyline>
                                            <line x1="16" y1="13" x2="8" y2="13"></line>
                                            <line x1="16" y1="17" x2="8" y2="17"></line>
                                            <polyline points="10 9 9 9 8 9"></polyline>
                                        </svg>
                                    </a>
                                </li> --}}
                            </ul>
                        </div>
                        <div class="chat-history chat-msg-box custom-scrollbar">
                            <ul id="chatingdata" style="padding-right: 0 !important">
                                @if ($data->isEmpty())
                                    <p class="text-center">لا توجد رسائل</p>
                                @else
                                    @foreach ($data as $key)
                                        @php
                                            $maxLength = 200;
                                        @endphp
                                        <li class="clearfix list-group-item"
                                            style="background-color: #FFF;border: none">
                                            <div class="message my-message pull-right w-100 m-0 p-2"
                                                style="background-color: #F4F7FB">
                                                <div class="message-data d-block d-flex">
                                                    <div style="flex: 1">
                                                        <img
                                                        style="width: 30px;height: 30px;border-radius: 50%;margin-top:-25px"
                                                    src="{{ asset('public/assets/images/avtar/profile.png') }}" alt=""
                                                    class="rounded-circle float-right chat-user-img img-30">
                                                    <div class="d-inline-block">
                                                        <p class="f-12 text-dark text-bold p-0 m-0">{{ $key->sender->name }}</p>
                                                        <p class="f-12 p-0 m-0">{{ $key->sender->email }}</p>
                                                    </div>
                                                    </div>
                                                    <div style="flex: 1" class="text-end">
                                                        <span style="font-size: 9px"
                                                            class="message-data-time text-bold">{{ \Carbon\Carbon::parse($key->created_at)->diffForHumans() }}</span>
                                                    </div>
                                                </div>
                                                <div>
                                                    @if (!empty($key->m_message_file))
                                                        <a class="btn btn-primary btn-xs"
                                                            href="{{ asset('public/storage/uploads/messages/' . $key->m_message_file) }}"
                                                            target="_blank"><span class="fa fa-download"></span>
                                                            <span>تحميل المرفق</span></a>
                                                        <br>
                                                    @endif
                                                    <p class="f-12 text-dark">
                                                        {{ $key->m_message_text }}
                                                        {{-- {{ strlen($key->m_message_text) > $maxLength ? substr($key->m_message_text, 0, $maxLength) . '...' : $key->m_message_text }} --}}
                                                    </p>
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                @endif
                            </ul>
                        </div>
                        <div class="chat-message clearfix mb-3">
                            <div class="row">
                                <div></div>
                                <div class="d-flex col-xl-12">
                                    <div class="text-box input-group"><input required
                                            placeholder="اكتب الرسالة هنا ....." type="text" class="form-control"
                                            id="mohamadmaraqa">
                                        <button type="button" class="btn btn-white btn-xs" onclick="openFileDialog()">
                                            <span class="fa fa-paperclip">
                                            </span>
                                        </button>
                                        <button type="button" onclick="add_message_ajax()"
                                            class="btn btn-primary btn-xs" style="font-size: 12px">ارسال</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <input type="file" id="fileInput" style="display: none;" onchange="handleFileSelect(event)" />
    <script>
        function add_message_ajax() {
            var message = $('#mohamadmaraqa').val();
            var fileInput = document.getElementById('fileInput'); // File input element
            var formData = new FormData();

            // Append message and other form data
            formData.append('m_conversation_id', $('#conversation_id').val());
            formData.append('m_message_text', message);

            // Append file if selected
            if (fileInput.files.length > 0) {
                formData.append('image', fileInput.files[0]);
            }

            // Send AJAX request
            $.ajax({
                url: "{{ route('training_supervisor.conversation.add_message_ajax') }}",
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: formData,
                processData: false, // Prevent jQuery from automatically processing the data
                contentType: false, // Prevent jQuery from setting the Content-Type header
                success: function(response) {
                    list_message_ajax($('#conversation_id').val());
                    $('.chat-msg-box').scrollTop($('.chat-msg-box')[0].scrollHeight);
                },
                error: function(error) {
                    alert('Error: ' + error.responseText);
                }
            });
        }

        function openFileDialog() {
            document.getElementById('fileInput').click();
        }

        function handleFileSelect(event) {
            const file = event.target.files[0];
            if (file) {
                // alert(`Selected file: ${file.name}`);
            }
        }

        $("#mohamadmaraqa").on("keypress", function(event) {
            if (event.which === 13) { // 13 هو كود مفتاح Enter
                event.preventDefault(); // منع إعادة تحميل الصفحة
                add_message_ajax();
            }
        });
    </script>
