<?php

namespace App\Http\Controllers;

use App\DataTables\OrderActivityDataTable;
use App\DataTables\OrdersDataTable;
use App\Enum\OrderStatus;
use App\Events\OrderCancelledEvent;
use App\Events\OrderReceivedEvent;
use App\Http\Requests\NewOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Traits\OrderService;
use Faker\Generator as Faker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;


class OrderController extends Controller
{
    use OrderService;
    /**
     * Display a listing of the resource.
     *
     * @return bool|\Illuminate\Auth\Access\Response|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function index(OrdersDataTable $dataTable)
    {
        abort_if(!\auth()->user()->can('view_order'), 403);
        return $dataTable->render('order.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return bool|\Illuminate\Auth\Access\Response|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function create()
    {
        abort_if(!\auth()->user()->can('add_order'), 403);
        $products = Product::all();
        return view('order.new', [
            'products' => $products
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\NewOrderRequest  $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Routing\Redirector
     */
    public function store(NewOrderRequest $request)
    {
        $order = Order::create($request->validated());

        event(new OrderReceivedEvent($order));

        Session::flash('message','Order was successfully created');
        Session::flash('m-class','alert-success');
        return redirect('order');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return bool|\Illuminate\Auth\Access\Response|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function show(int $id)
    {
        $order = Order::findOrFail($id);
        return view('order.show')->with('order', $order);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return bool|\Illuminate\Auth\Access\Response|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function edit(int $id)
    {
        $products = Product::all();
        $order = Order::find($id);
        return view('order.edit')->with([
            'order' => $order,
            'products' => $products
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateOrderRequest  $request
     * @param int $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, Faker $faker, int $id)
    {
        $this->updateOrderStatus($request, $faker, $id);

        Session::flash('message','Order was successfully updated');
        Session::flash('m-class','alert-success');

        return redirect('order');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function cancel(Request $request)
    {
        $order = Order::find($request->order);
        $order->cancel($request->comment);

        event(new OrderCancelledEvent($order));

        Session::flash('message','Order was successfully cancelled');
        Session::flash('m-class','alert-success');

        return response()->json([
            'status' => 200,
            'message' => 'Order Successfully Cancelled'
        ]);
    }


    public function activity(OrderActivityDataTable $dataTable, $order_id)
    {
        $order = Order::findOrFail($order_id);
        return $dataTable->with(['order_id' => $order_id])->render('order.activity', [
            'order' => $order
        ]);
    }
}
