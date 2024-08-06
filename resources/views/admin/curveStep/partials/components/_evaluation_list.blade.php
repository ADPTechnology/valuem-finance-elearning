<table class="table table-hover">
    <thead>
        <tr>
            <th>N°</th>
            <th>Titulo</th>
            <th>Duración (minutos)</th>
            <th>Estado</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($step->exams as $exam)
            <tr>
                <td>{{ $exam->id }}</td>
                <td> <a href="{{ route('admin.forgettingCurve.steps.evaluation.showQuestions', $exam) }}"> {{ $exam->title }} </a> </td>
                <td>{{ $exam->exam_time }}</td>

                @php
                    $isActive = $exam->active == 'S' ? 'active' : 'inactive';
                    $nameActive = $exam->active == 'S' ? 'Activo' : 'Inactivo';
                    $span = '<span class="status ' . $isActive . '">' . $nameActive . '</span>';
                @endphp

                <td>
                    {!! $span !!}
                </td>

                @php
                    $btn =
                        '<button data-toggle="modal" data-id=""
                        data-url="' .
                        route('admin.forgettingCurve.steps.updateExam', $exam) .
                        '"
                        data-send="' .
                        route('admin.forgettingCurve.steps.editExam', $exam) .
                        '"
                                        data-original-title="edit" class="me-3 edit btn btn-warning btn-sm
                                        editExam-btn"><i class="fa-solid fa-pen-to-square"></i></button>';
                    if ($exam->events_count == 0 && $exam->questions_count == 0) {
                        $btn .=
                            '<a href="javascript:void(0)" data-id="' .
                            $exam->id .
                            '"
                            data-url="' .
                            route('admin.forgettingCurve.steps.deleteExam', $exam) .
                            '"
                            data-original-title="delete"
                            data-url="" class="ms-3 edit btn btn-danger btn-sm
                            deleteExam-btn"><i class="fa-solid fa-trash-can"></i></a>';
                    }
                @endphp

                <td>
                    {!! $btn !!}
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
