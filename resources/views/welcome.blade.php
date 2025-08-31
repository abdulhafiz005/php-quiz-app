@extends('layouts.app')

@section('content')
    <div class="box" id="welcome-section">
        <form id="start-form">
            @csrf
            <label>Enter your name</label>
            <input type="text" name="name" required>
            <button type="submit">Next</button>
        </form>
    </div>

    <div id="quiz-section" style="display: none;"></div>
    <div id="result-section" style="display: none;"></div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var currentQuestionId = null;

        $('#start-form').submit(function(e) {
            e.preventDefault();
            $.ajax({
                url: '/start',
                type: 'POST',
                data: $(this).serialize(),
                success: function() {
                    $('#welcome-section').hide();
                    loadQuiz();
                },
                error: function(xhr) {
                    alert('Error submitting name: ' + (xhr.responseJSON?.error ?? 'Unknown error'));
                }
            });
        });

        function loadQuiz() {
            $.ajax({
                url: '/quiz',
                type: 'GET',
                success: function(data) {
                    if (data.finished) {
                        loadResult();
                        return;
                    }
                    renderQuestion(data);
                },
                error: function() {
                    alert('Error loading quiz');
                }
            });
        }

        function submitAnswer(optionId) {
            $.ajax({
                url: '/answer',
                type: 'POST',
                contentType: 'application/json',
                data: JSON.stringify({
                    question_id: currentQuestionId,
                    option_id: optionId
                }),
                success: function(data) {
                    if (data.finished) {
                        loadResult();
                    } else {
                        renderQuestion(data);
                    }
                },
                error: function(xhr) {
                    alert('Error submitting answer: ' + (xhr.responseJSON?.error ?? 'Unknown error'));
                }
            });
        }

        function loadResult() {
            $.ajax({
                url: '/result',
                type: 'GET',
                success: function(data) {
                    $('#quiz-section').hide();
                    var resultHtml = `
                        <div class="box" id="result-content">
                            <h3>Result Page</h3>
                            <p>Correct Ans (${data.correct})</p>
                            <p>Wrong Ans (${data.wrong})</p>
                            <p>Skipped Ans (${data.skipped})</p>
                        </div>`;
                    $('#result-section').html(resultHtml).show();
                },
                error: function() {
                    alert('Error loading result');
                }
            });
        }

        function renderQuestion(data) {
            var html = `
                <div class="box" id="quiz-content">
                    <div class="question">${data.question}</div>
                    <div class="options">`;

            for (var id in data.options) {
                html += `<label><input type="radio" name="answer" value="${id}"> ${data.options[id]}</label><br>`;
            }

            html += `
                    </div>
                    <button id="skip">Skip</button>
                    <button id="next">Next</button>
                </div>`;

            $('#quiz-section').html(html).show();
            currentQuestionId = data.question_id;
        }

        $(document).on('click', '#next', function() {
            var selected = $('input[name="answer"]:checked').val();
            if (!selected && $('input[name="answer"]').length > 0) {
                alert('Please select an option');
                return;
            }
            submitAnswer(selected);
        });

        $(document).on('click', '#skip', function() {
            submitAnswer(null);
        });
    </script>
@endsection
