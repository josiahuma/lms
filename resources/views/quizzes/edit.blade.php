<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold">✏️ Edit Quiz for: {{ $quiz->lesson->title }}</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto bg-white p-6 rounded shadow border">
            {{-- ✅ Main Update Quiz Form --}}
            <form action="{{ route('quizzes.update', $quiz) }}" method="POST" id="quiz-update-form">
                @csrf
                @method('PUT')

                <div id="questions-container">
                    @foreach ($quiz->questions as $qIndex => $question)
                        <div class="mb-6 border p-4 rounded shadow-sm question-block bg-gray-50">
                            <div class="flex justify-between items-start">
                                <h3 class="text-lg font-semibold mb-2">Question {{ $qIndex + 1 }}</h3>

                                {{-- ❌ Delete Button --}}
                                <button type="button"
                                        class="text-red-600 text-sm ml-4"
                                        onclick="submitDeleteForm({{ $question->id }})">
                                    🗑️ Delete
                                </button>
                            </div>

                            <label class="block mb-1 font-medium">Question Text:</label>
                            <input type="text" name="questions[{{ $qIndex }}][question]" class="w-full border rounded px-3 py-2 mb-3 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                   value="{{ $question->question }}" required>

                            <label class="block mb-1 font-medium">Answers:</label>
                            @foreach ($question->answers as $aIndex => $answer)
                                <div class="flex items-center gap-2 mb-2">
                                    <input type="radio" name="questions[{{ $qIndex }}][correct_answer]" value="{{ $aIndex }}" @if($answer->is_correct) checked @endif required>
                                    <input type="text" name="questions[{{ $qIndex }}][answers][{{ $aIndex }}][answer_text]"
                                           class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                           value="{{ $answer->answer_text }}" required>
                                </div>
                            @endforeach

                            <input type="hidden" name="questions[{{ $qIndex }}][id]" value="{{ $question->id }}">
                        </div>
                    @endforeach
                </div>

                {{-- ➕ Add New Question --}}
                <button type="button" onclick="addQuestion()" class="bg-yellow-400 hover:bg-yellow-500 text-black font-semibold px-4 py-2 rounded mb-6">
                    ➕ Add New Question
                </button>

                {{-- ✅ Submit --}}
                <div>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded">
                        ✅ Update Quiz
                    </button>
                </div>
            </form>

            {{-- Hidden Delete Forms --}}
            @foreach ($quiz->questions as $question)
                <form id="delete-form-{{ $question->id }}" action="{{ route('questions.destroy', $question) }}" method="POST" style="display: none;">
                    @csrf
                    @method('DELETE')
                </form>
            @endforeach
        </div>
    </div>

    <script>
        let questionCount = {{ $quiz->questions->count() }};

        function addQuestion() {
            const container = document.getElementById('questions-container');

            const html = `
                <div class="mb-6 border p-4 rounded shadow-sm question-block bg-gray-50">
                    <h3 class="text-lg font-semibold mb-2">New Question</h3>

                    <label class="block mb-1 font-medium">Question Text:</label>
                    <input type="text" name="questions[${questionCount}][question]" class="w-full border rounded px-3 py-2 mb-3 focus:outline-none focus:ring-2 focus:ring-indigo-500" required>

                    <label class="block mb-1 font-medium">Answers:</label>
                    ${[0, 1, 2, 3].map(i => `
                        <div class="flex items-center gap-2 mb-2">
                            <input type="radio" name="questions[${questionCount}][correct_answer]" value="${i}" required>
                            <input type="text" name="questions[${questionCount}][answers][${i}][answer_text]"
                                   class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                   placeholder="Option ${String.fromCharCode(65 + i)}" required>
                        </div>
                    `).join('')}
                </div>
            `;

            container.insertAdjacentHTML('beforeend', html);
            questionCount++;
        }

        function submitDeleteForm(questionId) {
            if (confirm('Are you sure you want to delete this question?')) {
                document.getElementById(`delete-form-${questionId}`).submit();
            }
        }
    </script>
</x-app-layout>
