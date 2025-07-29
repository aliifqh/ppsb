@extends('layouts.santri')
@section('title', 'Informasi Ujian')

@section('content')
<div class="bg-white rounded-lg shadow-md p-4 md:p-6 max-w-4xl mx-auto">
    <div class="max-w-6xl mx-auto">
        <div id="ujian-vue"
            data-santri='@json($santri)'
            data-progress='@json($progress)'
            data-tes-lisan='@json($tesLisanData)'
            data-tes-tulis='@json($tesTulisData)'
            data-tes-psikotes='@json($tesPsikotesData)'
            data-tes-kesehatan='@json($tesKesehatanData)'>
        </div>

    </div>
</div>

@endsection
