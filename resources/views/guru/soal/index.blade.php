@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Kelola Soal Ujian</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="/guru">Dashboard</a></div>
            <div class="breadcrumb-item active"><a href="{{ route('guru.ujian.index') }}">Daftar Ujian</a></div>
            <div class="breadcrumb-item">Soal Ujian</div>
        </div>
    </div>

    <div class="section-body">
        <h2 class="section-title">Kelola Soal</h2>
        <p class="section-lead">Silakan isi form di bawah ini untuk menambahkan soal ujian.</p>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <h4 class="mb-0">Form Tambah Soal</h4>
                        <div class="ml-auto">
                            <button type="button" class="btn btn-success" onclick="addQuestion()">
                                <i class="fas fa-plus"></i> Tambah Soal
                            </button>
                            <button type="button" class="btn btn-warning ml-2" onclick="resetForm()">
                                <i class="fas fa-undo"></i> Reset Form
                            </button>
                            <button type="button" class="btn btn-primary ml-2" data-toggle="modal" data-target="#modalLihatSoal">
                                <i class="fas fa-eye"></i> Lihat Soal
                            </button>
                            <form action="{{ route('guru.soal.reset', $ujian->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger ml-2">
                                    <i class="fas fa-trash"></i> Hapus Semua Soal
                                </button>
                            </form>

                            @if(\App\Models\SoalUjian::where('ujian_id', $ujian->id)->exists())
                                <a href="{{ route('guru.soal.edit', $ujian->id) }}" class="btn btn-info ml-2">
                                    <i class="fas fa-edit"></i> Edit Soal
                                </a>
                            @endif
                        </div>
                    </div>
                    <div class="card-body">
                        <form id="soal-form" action="{{ route('guru.soal.store', $ujian->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div id="questions-container"></div>

                            <div class="form-group mt-3">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Simpan Semua Soal
                                </button>
                                <a href="{{ route('guru.soal.index', $ujian->id) }}" class="btn btn-danger">Batal</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="modal fade" id="modalLihatSoal" tabindex="-1" role="dialog" aria-labelledby="modalLihatSoalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLihatSoalLabel">Preview Soal :</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="list-soal">
                    @foreach($soalList as $index => $soal)
                        <div class="border p-3 mb-3">
                            <h5>Soal {{ $index + 1 }}</h5>
                            <p><strong>Pertanyaan:</strong> {{ $soal->pertanyaan }}</p>

                            @if($soal->file_path)
                                <p><strong>Gambar:</strong></p>
                                <img src="{{ asset('storage/' . $soal->file_path) }}" class="img-thumbnail" style="max-width: 200px;">
                            @endif

                            <p><strong>Pilihan Jawaban:</strong></p>
                            <ul>
                                <li>A. {{ $soal->pilihan_a }}</li>
                                <li>B. {{ $soal->pilihan_b }}</li>
                                <li>C. {{ $soal->pilihan_c }}</li>
                                <li>D. {{ $soal->pilihan_d }}</li>
                            </ul>

                            <p><strong>Kunci Jawaban:</strong> {{ $soal->jawaban }}</p>
                            <p><strong>Bobot:</strong> {{ $soal->skor }}</p>
                        </div>
                    @endforeach
                    <div id="list-soal-baru"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    let questionIndex = 0;

    function addQuestion() {
        questionIndex++;

        let questionHtml = `
            <div class="border p-3 mb-3 question-item" id="question-${questionIndex}">
                <h5>Soal ${questionIndex}</h5>
                <div class="form-group">
                    <label for="pertanyaan-${questionIndex}">Pertanyaan</label>
                    <textarea class="form-control" name="pertanyaan[]" id="pertanyaan-${questionIndex}" required></textarea>
                </div>

                <div class="form-group">
                    <label for="file_path-${questionIndex}">Gambar (Opsional)</label>
                    <input type="file" class="form-control" name="file_path[]" id="file_path-${questionIndex}" onchange="previewImage(event, ${questionIndex})">
                    <img id="imagePreview-${questionIndex}" class="img-thumbnail mt-2" style="max-width: 200px; display: none;">
                </div>

                <div class="form-group">
                    <label>Pilihan Jawaban</label>
                    ${generateChoices(questionIndex)}

                    <label for="jawaban-${questionIndex}">Kunci Jawaban</label>
                    <select class="form-control" name="jawaban[]" id="jawaban-${questionIndex}" required>
                        <option value="A">A</option>
                        <option value="B">B</option>
                        <option value="C">C</option>
                        <option value="D">D</option>
                    </select>

                    <label for="skor-${questionIndex}">Bobot Jawaban</label>
                    <input type="number" class="form-control" name="skor[]" id="skor-${questionIndex}" min="0" required>
                </div>

                <button type="button" class="btn btn-danger btn-sm btn-remove" onclick="removeQuestion(${questionIndex})">Hapus Soal</button>
            </div>
        `;

        document.getElementById('questions-container').insertAdjacentHTML('beforeend', questionHtml);
        toggleRemoveButton();
    }

    function generateChoices(index) {
        let choices = ['A', 'B', 'C', 'D'];
        let choiceHtml = '';

        choices.forEach(choice => {
            choiceHtml += `
                <div class="input-group mb-2">
                    <span class="input-group-text">${choice}</span>
                    <input type="text" name="pilihan_${choice.toLowerCase()}[]" class="form-control" required>
                </div>
            `;
        });

        return choiceHtml;
    }

    function previewImage(event, index) {
        let input = event.target;
        let reader = new FileReader();

        reader.onload = function() {
            let imgElement = document.getElementById(`imagePreview-${index}`);
            imgElement.src = reader.result;
            imgElement.style.display = "block";
        };

        if (input.files && input.files[0]) {
            reader.readAsDataURL(input.files[0]);
        }
    }

    function removeQuestion(index) {
        document.getElementById(`question-${index}`).remove();
        toggleRemoveButton();
    }

    function resetForm() {
        Swal.fire({
            title: "Reset Form?",
            text: "Semua soal yang telah diinput akan dihapus!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya, Reset!",
            cancelButtonText: "Batal"
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('questions-container').innerHTML = "";
                questionIndex = 0;
                addQuestion();
            }
        });
    }

    function toggleRemoveButton() {
        let btns = document.querySelectorAll('.btn-remove');
        if (btns.length === 1) {
            btns[0].style.display = "none";
        } else {
            btns.forEach(btn => btn.style.display = "inline-block");
        }
    }

    function updateQuestionList() {
    let questions = document.querySelectorAll('.question-item');
    let listSoalBaru = document.getElementById('list-soal-baru');
    listSoalBaru.innerHTML = "<h5>Preview Soal (Belum Diupload)</h5>";

    if (questions.length === 0) {
        listSoalBaru.innerHTML += "<p>Belum ada soal yang ditambahkan.</p>";
    }

    questions.forEach((question, index) => {
        let soalText = question.querySelector('textarea').value || `Soal ${index + 1}`;
        let jawabanA = question.querySelector('input[name="pilihan_a[]"]').value || "-";
        let jawabanB = question.querySelector('input[name="pilihan_b[]"]').value || "-";
        let jawabanC = question.querySelector('input[name="pilihan_c[]"]').value || "-";
        let jawabanD = question.querySelector('input[name="pilihan_d[]"]').value || "-";
        let kunciJawaban = question.querySelector('select[name="jawaban[]"]').value;
        let skor = question.querySelector('input[name="skor[]"]').value || "0";

        listSoalBaru.innerHTML += `
            <div class="border p-3 mb-3">
                <h5>Soal ${index + 1}</h5>
                <p><strong>Pertanyaan:</strong> ${soalText}</p>
                <p><strong>Pilihan Jawaban:</strong></p>
                <ul>
                    <li>A. ${jawabanA}</li>
                    <li>B. ${jawabanB}</li>
                    <li>C. ${jawabanC}</li>
                    <li>D. ${jawabanD}</li>
                </ul>
                <p><strong>Kunci Jawaban:</strong> ${kunciJawaban}</p>
                <p><strong>Bobot:</strong> ${skor}</p>
            </div>
        `;
    });
}

    document.addEventListener('DOMContentLoaded', function() {
        addQuestion();

        $('#modalLihatSoal').on('show.bs.modal', updateQuestionList);
    });
</script>
@endsection
