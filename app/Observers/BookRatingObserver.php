<?php

namespace App\Observers;

use App\Models\BookRating;

class BookRatingObserver
{
    /**
     * Handle the BookRating "created" event.
     */

    public function created(BookRating $rating)
    {
        $this->updateBookRating($rating->book);
    }

    public function updated(BookRating $rating)
    {
        $this->updateBookRating($rating->book);
    }

    public function deleted(BookRating $rating)
    {
        $this->updateBookRating($rating->book);
    }

    protected function updateBookRating($book)
    {
        $book->updateAverageRating();
    }

    /**
     * Handle the bookRating "restored" event.
     */
    public function restored(BookRating $bookRating): void
    {
        //
    }

    /**
     * Handle the BookRating "force deleted" event.
     */
    public function forceDeleted(BookRating $bookRating): void
    {
        //
    }
}
