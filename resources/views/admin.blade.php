@extends('layout')

@section('content')
<div class="body">
    <table class="table">
        <tr>
            <th>Moyenne : <span class="badge bg-secondary">{{$average}}</span></th>
            <th>NPS : <span class="badge bg-secondary">{{$NPS}}</span></th>
        </tr>
    </table>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    @if (!(($stats['detracteurs'] == '0') && ($stats['passifs'] == '0') && ($stats['promoteurs'] == '0')))
        <div style="display: flex; margin:0 auto; width:300px;">
            <canvas id="myChart"></canvas>
        </div>
    @endif

    <script>
        Chart.defaults.plugins.legend.position ="right";
    const data = {
        labels: [
            "Détracteurs",
            "Passifs",
            "Promoteurs"
        ],
        position: "right",
        datasets: [{
            label: "My First Dataset",
            data: [{{$stats['detracteurs']}}, {{$stats['passifs']}}, {{$stats['promoteurs']}}],
            backgroundColor: [
                "rgb(255, 99, 132)",
                "rgb(255, 205, 86)",
                "rgb(54, 162, 235)"
            ],
            hoverOffset: 4
        }]
    };
    const config = {
        type: "doughnut",
        data: data,
    };

    var myChart = new Chart(
        document.getElementById("myChart"),
        config
    );
    </script>

    <p style="margin-bottom: 1.5rem">Le Net Promoter Score est un indice qui permet de mesurer la satisfaction d'une marque, 
    d'un produit ou d'un service. Il est calculé à partir de l’intention de recommandation d'un produit, 
    d'un service, d'une marque ou d'une entreprise. 
    <a target="_blank" href="https://www.bluenote-systems.com/faq-crm-sugarcrm/nps-definition-utilisation.html">plus d'info</a></p>
    <h3 class="display-6">Notes</h3>
    @if ($notes->isNotEmpty())
        <table class="table table-hover" style="margin-bottom: 1.5rem;">
            <tr>
                <th scope="col">ID</th>
                <th scope="col">ID Client</th>
                <th scope="col">Note</th>
                <th scope="col">Date</th>
                <th scope="col">Actions</th>
            </tr>
            @foreach($notes as $note)
                <tr>
                    <th scope="row">{{ $note->id }}</th>
                    <th>{{ $note->id_client }}</th>
                    <td>{{ $note->rating }}</td>
                    <td>{{ $note->updated_at->locale('fr_FR')->setTimezone('Europe/Paris')->isoFormat('LLLL:ss') }}</td>
                    <td>
                        <form action="/admin/deleteNote/{{ $note->id }}" method="post">
                            {{ csrf_field() }}
                            <button type="submit" class="btn btn-outline-danger btn-sm">Supprimer</button>
                            <input type="hidden" value="{{ $note->id }}" name="id">
                        </form>
                    </td>
                </tr>
            @endforeach
        </table>

        <div class="d-grid gap-2 col-6 mx-auto">
            <button style="margin-bottom: 2rem;" type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                Supprimer tout
            </button>
        </div>
    @else
        <p style="text-align:center">Aucune note enregistrée.</p>
    @endif

    <h3 class="display-6">Clients</h3>
    @if ($clients->isNotEmpty())
        <table class="table table-hover" style="margin-bottom: 1.5rem;">
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Nom</th>
                <th scope="col">Email</th>
                <th scope="col">Date</th>
                <th scope="col">Actions</th>
            </tr>
            @foreach($clients as $client)
                <tr>
                    <th scope="row">{{ $client->id }}</th>
                    <th>{{ $client->name }}</th>
                    <th>{{ $client->email }}</th>
                    <td>{{ $client->updated_at->locale('fr_FR')->setTimezone('Europe/Paris')->isoFormat('LLLL:ss') }}</td>
                    <td>
                        <form action="/admin/deleteClient/{{ $client->id }}" method="post">
                            {{ csrf_field() }}
                            <button type="submit" class="btn btn-outline-danger btn-sm">Supprimer</button>
                            <input type="hidden" value="{{ $client->id }}" name="id">
                        </form>
                    </td>
                </tr>
            @endforeach
        </table>
    @else
        <p style="text-align:center">Aucun client enregistré.</p>
    @endif

    <div class="d-grid gap-2 col-6 mx-auto">
        <button style="margin-bottom: 2rem;" type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop2">
            Ajouter un client
        </button>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Suppression des notes</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Êtes-vous sûr?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <form method="post" action="/admin/deleteAll">
                        {{ csrf_field() }}
                        <div>
                            <input type="submit" class="btn btn-danger" value="Supprimer tout">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="staticBackdrop2" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="post" action="{{ route('register-client') }}">
                {{ csrf_field() }}
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Ajout d'un client</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <input type="text" name="name" placeholder="Nom" id="name" required class="form-control @error('name') is-invalid @enderror ">
                        </div>
                        <div class="mb-3">
                            <input type="email" name="email" placeholder="Email" id="email" required class="form-control @error('email') is-invalid @enderror ">
                            @if ($errors->has('email'))
                                <span class="text-danger">L'adresse mail est déjà utilisée.</span>
                            @endif
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <div>
                            <input type="submit" class="btn btn-primary" value="Ajouter">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection