<?php

namespace App\Http\Controllers;

use App\Models\ReviewsData;
use Illuminate\Http\Request;

class ReviewsDataController extends Controller
{
    /**
     * Display a listing of reviews.
     */
    public function index()
    {
        try {
            // Get all reviews with related product and client information
            $reviews = ReviewsData::with('product', 'client')->get();

            return response()->json($reviews);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error retrieving reviews',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created review.
     */
    public function store(Request $request)
    {
        try {
            // Validate the request data
            $request->validate([
                'reviewer' => 'required|string',
                'product_id' => 'required|exists:produits,id',
                'rating' => 'required|integer|min:1|max:5',
                'comment' => 'required|string',
                'date' => 'required|date',
            ]);

            // Create a new review
            $review = ReviewsData::create([
                'reviewer' => $request->input('reviewer'),
                'product_id' => $request->input('product_id'),
                'rating' => $request->input('rating'),
                'comment' => $request->input('comment'),
                'date' => $request->input('date'),
            ]);

            return response()->json([
                'message' => 'Review created successfully',
                'data' => $review
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error creating review',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified review.
     */
    public function show($id)
    {
        try {
            // Find the review by ID and include related product and client data
            $review = ReviewsData::with('product', 'client')->findOrFail($id);

            return response()->json($review);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Review not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Update the specified review.
     */
    public function update(Request $request, $id)
    {
        try {
            // Validate the request data
            $request->validate([
                'reviewer' => 'required|string',
                'product_id' => 'required|exists:produits,id',
                'rating' => 'required|integer|min:1|max:5',
                'comment' => 'required|string',
                'date' => 'required|date',
            ]);

            // Find the review and update it
            $review = ReviewsData::findOrFail($id);
            $review->update($request->all());

            return response()->json([
                'message' => 'Review updated successfully',
                'data' => $review
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error updating review',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified review.
     */
    public function destroy($id)
    {
        try {
            // Find and delete the review
            $review = ReviewsData::findOrFail($id);
            $review->delete();

            return response()->json(['message' => 'Review deleted successfully']);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error deleting review',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function countReviews()
    {
        try {
            // Count the number of subcategories
            $count = ReviewsData::count();
            return response()->json(['count' => $count]);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), $e->getCode());
        }
    }
    public function getProductReviewStats($id)
    {
        try {
            // Get all reviews for the given product ID
            $reviews = ReviewsData::where('product_id', $id)->get();

            // Calculate the total number of reviews
            $totalReviews = $reviews->count();

            if ($totalReviews === 0) {
                return response()->json([
                    'message' => 'No reviews found for this product.',
                    'data' => null
                ], 404);
            }

            // Calculate the average rating
            $averageRating = $reviews->avg('rating');

            // Calculate the distribution of ratings
            $ratingsDistribution = [];
            for ($i = 1; $i <= 5; $i++) {
                $ratingCount = $reviews->where('rating', $i)->count();
                $percentage = $ratingCount > 0 ? round(($ratingCount / $totalReviews) * 100) : 0;
                $ratingsDistribution[] = [
                    'rating' => $i,
                    'count' => $ratingCount,
                    'percentage' => $percentage
                ];
            }

            return response()->json([
                'productId' => $id,
                'averageRating' => number_format($averageRating, 1),
                'totalReviews' => $totalReviews,
                'ratingsDistribution' => $ratingsDistribution
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error calculating product review statistics',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
