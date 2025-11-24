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
        $this->updateBookRating($rating->course);
    }

    public function updated(BookRating $rating)
    {
        $this->updateBookRating($rating->course);
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
     * Handle the CourseRating "restored" event.
     */
    public function restored(BookRating $courseRating): void
    {
        //
    }

    /**
     * Handle the CourseRating "force deleted" event.
     */
    public function forceDeleted(BookRating $courseRating): void
    {
        //
    }
}
