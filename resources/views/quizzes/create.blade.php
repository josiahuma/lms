<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold">Create Quiz for: {{ $lesson->title }}</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto bg-white p-6 rounded shadow border">
            <form action="{{ route('quizzes.store', $lesson) }}" method="POST" id="quiz-form">
                @csrf

                <div id="questions-container">
                    <!-- Initial Question -->
                    <div class="question-block mb-6 border p-4 rounded shadow-sm bg-gray-50">
                        <h3 class="text-lg font-semibold mb-2">Question 1</h3>

                        <label class="block mb-1 font-medium">Question:</label>
                        <input type="text" name="questions[0][question]" class="w-full border rounded px-3 py-2 mb-3 focus:outline-none focus:ring-2 focus:ring-indigo-500" required>

                        <label class="block mb-1 font-medium">Answers:</label>
                        @foreach (['A', 'B', 'C', 'D'] as $index => $label)
                            <div class="flex items-center gap-2 mb-2">
                                <input type="radio" name="questions[0][correct_answer]" value="{{ $index }}" required>
                                <input type="text" name="questions[0][answers][{{ $index }}][answer_text]" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Option {{ $label }}" required>
                            </div>
                        @endforeach
                    </div>
                </div>

                <button type="button" onclick="addQuestion()" class="bg-yellow-500 hover:bg-yellow-600 text-black font-semibold px-4 py-2 rounded mb-6">
                    ➕ Add Another Question
                </button>

                <div>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded">
                        ✅ Save Quiz
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        let questionIndex = 1;

        function addQuestion() {
            const container = document.getElementById('questions-container');

            const block = document.createElement('div');
            block.className = 'question-block mb-6 border p-4 rounded shadow-sm bg-gray-50';

            block.innerHTML = `
                <h3 class="text-lg font-semibold mb-2">Question ${questionIndex + 1}</h3>

                <label class="block mb-1 font-medium">Question:</label>
                <input type="text" name="questions[${questionIndex}][question]" class="w-full border rounded px-3 py-2 mb-3 focus:outline-none focus:ring-2 focus:ring-indigo-500" required>

                <label class="block mb-1 font-medium">Answers:</label>
                ${['A', 'B', 'C', 'D'].map((label, i) => `
                    <div class="flex items-center gap-2 mb-2">
                        <input type="radio" name="questions[${questionIndex}][correct_answer]" value="${i}" required>
                        <input type="text" name="questions[${questionIndex}][answers][${i}][answer_text]" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Option ${label}" required>
                    </div>
                `).join('')}
            `;

            container.appendChild(block);
            questionIndex++;
        }
    </script>
</x-app-layout>
