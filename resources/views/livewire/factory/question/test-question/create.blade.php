<div class="col-12 text-left mt-4 p-4">

    <div class="card shadow p-2">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">question test:</h6>
        </div>
        <div class="card-body">

            <input type="hidden" wire:model="questionTypeId" />
            <div class="form-group">
                <label for="titleofquestion">title of question</label>
                <input type="text" class="form-control" id="titleofquestion" wire:model="title">
            </div>
            <div class="form-group">
                <label for="descriptionofquestion">description of question</label>
                <textarea class="form-control ckeditor" id="descriptionofquestion" wire:model.defer="question_body"></textarea>
            </div>
            @forelse($answers as $index => $answer)
                <div class="form-group">
                    <label for="answer{{ $index }}">answer {{ $loop->iteration }}</label>
                    <div class="row">
                        <div class="col-11">
                            <input name="correctAnswer" value="{{ $index }}" wire:model="correctAnswer"
                                class="form-check-input" type="radio">
                            <textarea class="form-control ckeditor" id="answer{{ $index }}" wire:model.defer="answers.{{ $index }}"></textarea>
                        </div>
                        <div class="col-1">
                            <button class="btn btn-sm btn-danger btn-circle"
                                wire:click.prevent="removeAnswer('{{ $index }}')"><i
                                    class="fa fa-times"></i></button>
                        </div>
                    </div>
                </div>
            @empty
            @endforelse



            <button wire:click.prevent="addNewAnswer" class="btn btn-success btn-icon-split float-right">
                <span class="icon text-white-50">
                    <i class="fas fa-plus"></i>
                </span>
            </button>

            </form>

        </div>
        <div class="card-footer">
            <button wire:click.prevent="store" class="btn btn-primary btn-icon-split">
                <span class="icon text-white-50">
                    <i class="fas fa-plus"></i>
                </span>
                <span class="text">Save</span>
            </button>
        </div>
    </div>
    <form>



        @include('livewire.factory.question.test-question.review')
</div>


@push('scripts')
    <!-- Include jQuery (jika belum ada) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Include CKEditor 5 -->
    <script src="https://cdn.ckeditor.com/ckeditor5/35.1.0/classic/ckeditor.js"></script>

    <script>
        $(document).ready(function() {
            function initializeCKEditor(element, modelProperty) {
                ClassicEditor
                    .create(element, {
                        // Aktifkan Base64 upload adapter
                        simpleUpload: {
                            uploadUrl: '', // Kosongkan untuk menonaktifkan upload eksternal
                        },
                        ckfinder: {
                            uploadUrl: ''
                        },
                        toolbar: [
                            'heading', '|',
                            'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', '|',
                            'insertTable', 'mediaEmbed', 'undo', 'redo'
                        ],
                        // Tambahkan plugin yang diperlukan
                        image: {
                            toolbar: ['imageTextAlternative', 'imageStyle:full', 'imageStyle:side']
                        },
                        table: {
                            contentToolbar: ['tableColumn', 'tableRow', 'mergeTableCells']
                        }
                    })
                    .then(editor => {
                        editor.model.document.on('change:data', () => {
                            @this.set(modelProperty, editor.getData());
                        });
                    })
                    .catch(error => {
                        console.error(error);
                    });
            }

            // Inisialisasi CKEditor untuk Description of Question
            initializeCKEditor(document.querySelector('#descriptionofquestion'), 'question_body');

            // Inisialisasi CKEditor untuk setiap Answer
            @foreach ($answers as $index => $answer)
                initializeCKEditor(document.querySelector('#answer{{ $index }}'),
                    'answers.{{ $index }}');
            @endforeach

            // Re-inisialisasi CKEditor setelah Livewire memperbarui DOM
            Livewire.hook('message.processed', (message, component) => {
                @foreach ($answers as $index => $answer)
                    if (!document.querySelector('#answer{{ $index }}').dataset.editorInitialized) {
                        initializeCKEditor(document.querySelector('#answer{{ $index }}'),
                            'answers.{{ $index }}');
                        document.querySelector('#answer{{ $index }}').dataset.editorInitialized =
                            true;
                    }
                @endforeach
                if (!document.querySelector('#descriptionofquestion').dataset.editorInitialized) {
                    initializeCKEditor(document.querySelector('#descriptionofquestion'), 'question_body');
                    document.querySelector('#descriptionofquestion').dataset.editorInitialized = true;
                }
            });
        });
    </script>
@endpush
