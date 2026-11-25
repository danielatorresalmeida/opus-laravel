@extends('layouts.master')
@section('title', 'Fontes musicais')
@section('content')
    <section class="hero"><div class="hero-content"><span class="eyebrow">Investigação musical</span><h1>Fontes e arquivos.</h1><p>Descubra bibliotecas, catálogos e projetos especializados em música erudita.</p></div><img class="hero-mark" src="{{ asset('images/opus-mark.svg') }}" alt=""></section>
    <div class="section-head"><div><span class="eyebrow">Diretório de recursos</span><h2>Bibliotecas e catálogos</h2></div></div>
    <div class="cards">
        <section class="panel source-card"><span class="eyebrow">Partituras</span><h3>IMSLP</h3><p class="muted">Petrucci Music Library.</p><a class="btn btn-secondary" href="https://imslp.org/" target="_blank" rel="noopener noreferrer">Visitar biblioteca ↗</a></section>
        <section class="panel source-card"><span class="eyebrow">Música coral</span><h3>CPDL / ChoralWiki</h3><p class="muted">Repertório coral e vocal.</p><a class="btn btn-secondary" href="https://www.cpdl.org/" target="_blank" rel="noopener noreferrer">Visitar biblioteca ↗</a></section>
        <section class="panel source-card"><span class="eyebrow">Fontes históricas</span><h3>RISM</h3><p class="muted">Catálogo de fontes musicais.</p><a class="btn btn-secondary" href="https://rism.online/" target="_blank" rel="noopener noreferrer">Visitar catálogo ↗</a></section>
        <section class="panel source-card"><span class="eyebrow">Catálogo especializado</span><h3>Bach Digital</h3><p class="muted">Obras e fontes relacionadas com a família Bach.</p><a class="btn btn-secondary" href="https://www.bach-digital.de/" target="_blank" rel="noopener noreferrer">Visitar arquivo ↗</a></section>
        <section class="panel source-card"><span class="eyebrow">Catálogo especializado</span><h3>Digital Mozart Edition</h3><p class="muted">Edições e documentação de Mozart.</p><a class="btn btn-secondary" href="https://mozarteum.at/en/digital-mozart-edition" target="_blank" rel="noopener noreferrer">Visitar projeto ↗</a></section>
        <section class="panel source-card"><span class="eyebrow">Catálogo especializado</span><h3>Beethoven-Haus Bonn</h3><p class="muted">Património e documentação de Beethoven.</p><a class="btn btn-secondary" href="https://www.beethoven.de/" target="_blank" rel="noopener noreferrer">Visitar arquivo ↗</a></section>
        <section class="panel source-card"><span class="eyebrow">Património português</span><h3>Portuguese Early Music Database</h3><p class="muted">Fontes e manuscritos de música antiga.</p><a class="btn btn-secondary" href="https://pemdatabase.eu/" target="_blank" rel="noopener noreferrer">Visitar base de dados ↗</a></section>
        <section class="panel source-card"><span class="eyebrow">Património português</span><h3>MIC.PT</h3><p class="muted">Centro de Informação da Música Portuguesa.</p><a class="btn btn-secondary" href="https://mic.pt/" target="_blank" rel="noopener noreferrer">Visitar centro ↗</a></section>
        <section class="panel source-card"><span class="eyebrow">Dados musicais</span><h3>Open Opus</h3><p class="muted">Catálogo de compositores e obras.</p><a class="btn btn-secondary" href="https://openopus.org/" target="_blank" rel="noopener noreferrer">Visitar projeto ↗</a></section>
    </div>
    <p class="muted spaced">Antes de reproduzir documentos ou imagens, consulte as condições de utilização de cada fonte. Estes links não constituem uma importação automática.</p>
@endsection
