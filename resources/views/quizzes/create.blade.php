<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold">Create Quiz for: {{ $lesson->title }}</h2>
    </x-slot>

    <div class="py-6 px-4">
        <form action="{{ route('quizzes.store', $lesson) }}" method="POST" id="quiz-form">
            @csrf

            <div id="questions-container">
                <!-- One default question to start -->
                <div class="question-block mb-6 border p-4 rounded shadow-sm">
                    <h3 class="text-lg font-semibold">Question 1</h3>
                    <label class="block mb-1">Question:</label>
                    <input type="text" name="questions[0][question]" class="w-full border rounded px-3 py-2 mb-2" required>

                    <label class="block mb-1">Answers:</label>
                    @foreach (['A', 'B', 'C', 'D'] as $index => $label)
                        <div class="mb-1">
                            <input type="radio" name="questions[0][correct_answer]" value="{{ $index }}" required>
                            <input type="text" name="questions[0][answers][{{ $index }}][answer_text]" class="border rounded px-2 py-1 w-3/4" placeholder="Option {{ $label }}" required>
                        </div>
                    @endforeach
                </div>
            </div>

            <button type="button" onclick="addQuestion()" class="bg-yellow-500 text-black px-4 py-2 rounded mb-4">➕ Add Another Question</button>
            <br>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">✅ Save Quiz</button>
        </form>
    </div>

    <script>
        let questionIndex = 1;

        function addQuestion() {
            const container = document.getElementById('questions-container');

            const block = document.createElement('div');
            block.className = 'question-block mb-6 border p-4 rounded shadow-sm';

            block.innerHTML = `
                <h3 class="text-lg font-semibold">Question ${questionIndex + 1}</h3>
                <label class="block mb-1">Question:</label>
                <input type="text" name="questions[${questionIndex}][question]" class="w-full border rounded px-3 py-2 mb-2" required>

                <label class="block mb-1">Answers:</label>
                ${['A', 'B', 'C', 'D'].map((label, i) => `
                    <div class="mb-1">
                        <input type="radio" name="questions[${questionIndex}][correct_answer]" value="${i}" required>
                        <input type="text" name="questions[${questionIndex}][answers][${i}][answer_text]" class="border rounded px-2 py-1 w-3/4" placeholder="Option ${label}" required>
                    </div>
                `).join('')}
            `;

            container.appendChild(block);
            questionIndex++;
        }
    </script>
</x-app-layout>
