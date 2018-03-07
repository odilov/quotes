<?php

    namespace App\Http\Controllers;

    use Illuminate\Http\Request;
    use App\Author;
    use App\Quote;

    class QuoteController extends Controller{
        
        public function getIndex( $author = null ){
            if( $author != null and Author::where( 'name' , $author )->first() )
                return view( 'index' , [
                    'quotes' => Author::where( 'name' , $author )->first()->quotes()->orderBy( 'created_at' , 'desc' )->paginate(6)
                ]);
                
            return view( 'index' , ['quotes' => Quote::orderBy( 'created_at' , 'desc' )->paginate(6)] );
        }
        
        public function postQuote( Request $req ){
            $this->validate( $req , [
                'author' => 'required|alpha|max:20',
                'quote'  => 'required|max:200'
            ] );
            $author = Author::where( 'name' , ucfirst( $req['author'] ) )->first();
            if( !$author ){
                $author = new Author();
                $author->name = ucfirst( $req['author'] );
                $author->save();
            }
            $quote = new Quote();
            $quote->quote = $req['quote'];
            $author->quotes()->save( $quote );
            
            return redirect()->route( 'index' )->with( [
                'success' => 'quote saved!'
            ] );
        }

        public function deleteQuote( $quote_id ){
            $quote = Quote::find( $quote_id );
            $quth_deleted = false;
            if( count( $quote->author->quotes ) == 1 )
                $quote->author->delete();
                $auth_deleted = true;
            $quote->delete();
            return redirect()->route( 'index' )->with( [
                'success' => ( $auth_deleted ? 'author and quote deleted' : 'quote deleted' )
            ] );
        }

    }

?>