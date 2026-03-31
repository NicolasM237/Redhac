<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Détails des Violences</title>
    <style>
        body { font-family: sans-serif; font-size: 11px; color: #333; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; table-layout: fixed; }
        th, td { border: 1px solid #ccc; padding: 6px; text-align: left; word-wrap: break-word; }
        th { background-color: #f8f9fa; color: #555; text-transform: uppercase; font-size: 9px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #444; padding-bottom: 10px; }
        .section-title { background-color: #e9ecef; padding: 5px; font-weight: bold; margin-top: 15px; border: 1px solid #ccc; }
        .page-break { page-break-after: always; }
    </style>
</head>
<body>
    @foreach ($violences as $v)
    <div class="header">
        <h1>Fiche de Cas : {{ $v->code }}</h1>
        <p>Généré le : {{ date('d/m/Y H:i') }} | Status : <strong>{{ $v->status }}</strong></p>
    </div>

    <div class="section-title">1. INFORMATIONS VICTIME</div>
    <table>
        <thead>
            <tr>
                <th>Code</th>
                <th>Sexe</th>
                <th>Âge</th>
                <th>Nationalité</th>
                <th>Occupation</th>
                <th>Contact</th>
                <th>Résidence</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $v->code }}</td>
                <td>{{ $v->sexe }}</td>
                <td>{{ $v->age }} ans</td>
                <td>{{ $v->nationalite }}</td>
                <td>{{ $v->occupation }}</td>
                <td>{{ $v->contact }}</td>
                <td>{{ $v->residence }}</td>
            </tr>
        </tbody>
    </table>

    <div class="section-title">2. DÉTAILS DE L'INCIDENT</div>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Lieu</th>
                <th>Nature du cas</th>
                <th>Mode de Collecte</th>
                <th>Auteurs</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $v->datesurvenue ? \Carbon\Carbon::parse($v->datesurvenue)->format('d/m/Y') : 'N/A' }}</td>
                <td>{{ $v->lieusurvenue }}</td>
                <td>{{ $v->nature->nom ?? 'N/A' }}</td>
                <td>{{ $v->collecte->nom ?? 'N/A' }}</td>
                <td>{{ $v->auteurs }}</td>
            </tr>
        </tbody>
    </table>

    <div class="section-title">3. DESCRIPTION ET ANALYSE</div>
    <table>
        <tr>
            <th style="width: 25%;">Situation</th>
            <td>{{ $v->situation }}</td>
        </tr>
        <tr>
            <th>Description du cas</th>
            <td>{{ $v->description_cas }}</td>
        </tr>
        <tr>
            <th>Risques identifiés</th>
            <td>{{ $v->risque_victime }}</td>
        </tr>
        <tr>
            <th>Mesures prises (OBC)</th>
            <td>{{ $v->mesure_obc }}</td>
        </tr>
        <tr>
            <th>Attentes de la victime</th>
            <td>{{ $v->attente_victime }}</td>
        </tr>
    </table>

    {{-- Si ce n'est pas le dernier élément, on change de page pour le suivant --}}
    @if (!$loop->last)
        <div class="page-break"></div>
    @endif
    @endforeach
</body>
</html>