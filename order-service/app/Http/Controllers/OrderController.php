<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        return response()->json([
            'status' => 'success',
            'message' => 'Order list',
            'data' => Order::all()
        ]);
    }

    public function create(Request $request)
    {
        $fields = $request->validate([
            'user_id' => 'required|integer',
            'total' => 'required|numeric',
            'status' => 'required|string|in:pending,processing,completed,cancelled',
            'details' => 'required|array',
            'details.*.product_id' => 'required|integer',
            'details.*.quantity' => 'required|integer',
            'details.*.price' => 'required|numeric',
        ]);

        $order = Order::create([
            'order_number' => 'ORD-' . time() . '-' . $fields['user_id'],
            'user_id' => $fields['user_id'],
            'total' => $fields['total'],
            'status' => $fields['status'],
        ]);

        if (!$order) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create order'
            ], 500);
        }

        foreach ($fields['details'] as $detail) {
            OrderDetail::create([
                'order_id' => $order->id,
                'product_id' => $detail['product_id'],
                'quantity' => $detail['quantity'],
                'price' => $detail['price'],
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Order created successfully',
            'data' => $order
        ], 201);
    }

    public function show($id)
    {
        return response()->json([
            'status' => 'success',
            'message' => 'Order details',
            'data' => Order::with('details')->find($id)
        ]);
    }

    // Update order status and details if details are provided
    public function update(Request $request, $id)
    {
        $fields = $request->validate([
            'status' => 'string|in:pending,processing,completed,cancelled',
            'details' => 'sometimes|array',
            'details.*.id' => 'required|integer',
            'details.*.product_id' => 'required|integer',
            'details.*.quantity' => 'required|integer',
            'details.*.price' => 'required|numeric',
        ]);

        $order = Order::find($id);

        if (!$order) {
            return response()->json([
                'status' => 'error',
                'message' => 'Order not found'
            ], 404);
        }

        $order->status = $fields['status'];
        $order->save();

        if (isset($fields['details'])) {
            foreach ($fields['details'] as $detail) {
                $orderDetail = OrderDetail::find($detail['id']);

                if (!$orderDetail) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Order detail not found'
                    ], 404);
                }

                $orderDetail->product_id = $detail['product_id'];
                $orderDetail->quantity = $detail['quantity'];
                $orderDetail->save();
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Order updated successfully',
            'data' => Order::with('details')->find($id)
        ]);
    }

    public function delete($id)
    {
        $order = Order::find($id);

        if (!$order) {
            return response()->json([
                'status' => 'error',
                'message' => 'Order not found'
            ], 404);
        }

        $order->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Order deleted successfully'
        ]);
    }

    public function userOrders(Request $request)
    {
        return response()->json([
            'status' => 'success',
            'message' => 'User orders',
            'data' => Order::where('user_id', $request->user()->id)->with('details')->get()
        ]);
    }
}
