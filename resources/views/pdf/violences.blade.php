<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Export PDF Violences</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #333; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; }
        .header { text-align: center; margin-bottom: 30px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Rapport des Cas de Violences</h1>
        <p>Généré le : {{ date('d/m/Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Code</th>
                <th>Nationalité</th>
                <th>Sexe</th>
                <th>Nature</th>
                <th>Collecte</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($violences as $v)
            <tr>
                <td>{{ $v->code }}</td>
                <td>{{ $v->nationalite }}</td>
                <td>{{ $v->sexe }}</td>
                <td>{{ $v->nature->nom ?? 'N/A' }}</td>
                <td>{{ $v->collecte->nom ?? 'N/A' }}</td>
                <td>{{ \Carbon\Carbon::parse($v->datesurvenue)->format('d/m/Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>