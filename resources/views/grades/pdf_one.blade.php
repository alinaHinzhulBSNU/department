<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Відомість PDF</title>
    <style>
        body { 
            font-family: DejaVu Sans 
        }

        h3{
            text-align: center;
        }

        .footer {
            width: 100%;
            text-align: right;
            position: fixed;
            bottom: 15px;
            right: 20px; 
        }
        .pagenum:before {
            content: counter(page); 
            
        }
        table {
            width: 97%; 
            border-collapse: collapse; 
            border: 0px;
        }

        .page-break {
            page-break-before: always;
            }
        @page { margin: 20px 30px 40px 50px; }

        th {
            border: 1px solid; 
            padding:12px;
            width="30%; 
        }

        td {
            border: 1px solid; 
            padding: 12px;
            text-align: center; 
            width: 30%; 
        }
    </style> 
</head>
<body>
    <h3>Факультет Комп'ютерних Наук</h3>
    <h3>Група {{ $group->number }}</h3>
    <h3>Дисципліна: {{ $subject->name }}</h3> 
    <table>
        <tr>
            <th>Студент</th>
            <th>Бали</th>
            <th>ECTS</th>
            <th>Національна шкала</th>
        </tr>
        @php $counter = 3 @endphp
        @foreach($group->students as $student)
            <tr>
                @php $counter++ @endphp
                <td>{{ $student->user->name }}</td>
                <td> <!-- grade number --> 
                    @foreach($student->grades as $grade)
                        @if($grade->subject->id === $subject->id)
                            {{ $grade->grade }} 
                        @endif
                    @endforeach
                </td>
                <td> <!-- grade letter (ECTS) --> 
                    @foreach($student->grades as $grade)
                        @if($grade->subject->id === $subject->id)
                            {{ $grade->toECTS() }} 
                        @endif
                    @endforeach
                </td>
                <td> <!-- grade word (National Scale) --> 
                    @foreach($student->grades as $grade)
                        @if($grade->subject->id === $subject->id)
                            {{ $grade->toNational() }} 
                        @endif
                    @endforeach
                </td>
                <!--Only 19 rows on each page-->
                @if($counter == 19)
                </tr>
                </table>
                <div class="footer" style="margin-bottom: 20px;">
                    <span class="pagenum"></span>
                </div>
                <div class="page-break"></div>
                <table>
                    <tr>
                        <th>Студент</th>
                        <th>{{ $subject->name }}</th>
                    </tr>
                    @php $counter = 0 @endphp
                @else
                    </tr>
                @endif
            </tr>
        @endforeach
    </table>
    <div class="footer" style="margin-bottom: 20px;">
        <span class="pagenum"></span>
    </div>
</body>
</html>