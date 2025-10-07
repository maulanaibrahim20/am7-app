 <form action="{{ route('purchase-order.receiveSubmit', $purchaseOrder->id) }}" method="POST" id="ajxForm"
     data-ajxForm-reset="false">
     @csrf

     <div class="mb-4">
         <h5 class="fw-bold mb-1">Receive Items - {{ $purchaseOrder->po_number }}</h5>
         <p class="text-muted mb-0">
             Supplier: {{ $purchaseOrder->supplier->name ?? '-' }}<br>
             Order Date: {{ $purchaseOrder->order_date->format('d M Y') }}
         </p>
     </div>

     <div class="table-responsive">
         <table class="table table-bordered align-middle">
             <thead class="table-light text-center">
                 <tr>
                     <th>Product</th>
                     <th>Ordered</th>
                     <th>Already Received</th>
                     <th>Receive Now</th>
                 </tr>
             </thead>
             <tbody>
                 @foreach ($purchaseOrder->items as $i => $item)
                     <tr>
                         <td>
                             <strong>{{ $item->product->sku ?? '' }}</strong><br>
                             <span class="text-muted">{{ $item->product->name ?? '' }}</span>
                         </td>
                         <td class="text-center">{{ $item->quantity_ordered }}</td>
                         <td class="text-center">{{ $item->quantity_received }}</td>
                         <td>
                             <input type="number" name="items[{{ $item->id }}][quantity_received]"
                                 class="form-control text-end" min="0"
                                 max="{{ $item->quantity_ordered - $item->quantity_received }}" value="0">
                         </td>
                     </tr>
                 @endforeach
             </tbody>
         </table>
     </div>

     <div class="form-footer text-end mt-3">
         <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
             <i class="fas fa-times me-1"></i> Close
         </button>
         <button type="submit" class="btn btn-success">
             <i class="fas fa-box-open me-1"></i> Receive
         </button>
     </div>
 </form>
