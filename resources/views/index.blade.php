@extends( 'layouts.master' )

@section( 'title' )
    Trending quotes
@endsection

@section( 'styles' )
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
@endsection

@section( 'content' )
    <section class = "quotes">
        <h1>Latest Quotes</h1>

        @if( Request::segment( 1 ) )
            <section class = "filter-bar">
                <a href = "{{ route( 'index' ) }}"> show all quotes </a>
            </section>
        @endif

        @if( count( $errors ) > 0 )
            <section class = "info-box fail">
                @foreach( $errors->all() as $error )
                    {{ $error }}
                @endforeach
            </section>
        @endif

        @if( Session::has( 'success' ) )
            <section class = "info-box success">
                {{ Session::get( 'success' ) }}
            </section>
        @endif

        @for( $i = 0; $i < count( $quotes ); ++$i )
            <article class = "quote{{ $i % 3 == 3 ? ' first-in-line' : ( ( $i + 1 ) % 3 == 0 ? ' last-in-line' : '' ) }}">
                <div class = "delete"><a href="{{ route( 'delete' , ['quote_id' => $quotes[$i]->id] ) }}">x</a></div>
                    {{ $quotes[$i]->quote }}
                <div class = "info">
                    Author <a href="{{ route( 'index' , ['author' => $quotes[$i]->author->name] ) }}">{{ $quotes[$i]->author->name }}</a>
                     on {{ $quotes[$i]->created_at }} </div>
            </article>
        @endfor
        <div class = "pagination">
            @if( $quotes->currentPage() != 1 )
                <a href="{{ $quotes->previousPageUrl() }}"><span class = "fa fa-caret-left"></span></a>
            @endif
            @if( $quotes->hasPages() and $quotes->currentPage() != $quotes->lastPage() )
                <a href="{{ $quotes->nextPageUrl() }}"><span class = "fa fa-caret-right"></span></a>
            @endif
        </div>
    </section>
    <section class = "edit-quote">
        <h1>New Quote</h1>
        <form method = "post" action = "{{ route( 'create' ) }}"> 
            <div class = "input-group">
                <label for="author">Name</label>
                <input type="text" name = "author" id = "author" />
            </div>
            <div class = "input-group">
                <label for="quote">Quote</label>
                <textarea name="quote" id="quote" cols="20" rows="5"></textarea>
            </div>
            <input type="hidden" name = "_token" value = "{{ Session::token() }}" />
            <button class = "btn" type = "submit">send</button>
        </form>
    </section>
@endsection
