package vn.edu.example.BanHang_CNPM;

import androidx.annotation.Nullable;
import androidx.appcompat.app.AppCompatActivity;
import androidx.appcompat.widget.Toolbar;
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;

import android.content.Context;
import android.net.ConnectivityManager;
import android.net.NetworkInfo;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.widget.Toast;

import com.android.volley.AuthFailureError;
import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.StringRequest;
import com.android.volley.toolbox.Volley;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

import vn.edu.example.BanHang_CNPM.Adapter.Adapter_DonHang;
import vn.edu.example.BanHang_CNPM.DTO.DTO_DonHang;
import vn.edu.example.BanHang_CNPM.Server.Server;

public class DonHangActivity extends AppCompatActivity {
    private Toolbar toolbar;
    private RecyclerView rcv_donhang;
    private int id_nguoidung;
    private List<DTO_DonHang>list_donhang;

    private Adapter_DonHang adapter_donHang;
    private int id_donhang,soluong;
    private String ten_sp,hinh_sp,gia_sp,tongtien;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_don_hang);
        setTitle("Đơn Hàng ");
        AnhXa();
        ActionBar();
        if(Check_Internet(this)){
            getDonHang();
        }else {
            Toast.makeText(getApplicationContext(),"không có kết nối internet",Toast.LENGTH_SHORT).show();
        }
    }
    private void AnhXa(){
        toolbar=findViewById(R.id.toolbar);
        rcv_donhang=findViewById(R.id.rcv_donhang);
        list_donhang=new ArrayList<>();
        LinearLayoutManager layoutManager=new LinearLayoutManager(getApplicationContext());
        rcv_donhang.setLayoutManager(layoutManager);
        adapter_donHang=new Adapter_DonHang(getApplicationContext(),list_donhang);
        rcv_donhang.setAdapter(adapter_donHang);


    }

    private void ActionBar(){

        setSupportActionBar(toolbar);
        getSupportActionBar().setDisplayHomeAsUpEnabled(true);
        toolbar.setNavigationOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                finish();
            }
        });


    }

    //
    private void getDonHang() {
        RequestQueue requestQueue = Volley.newRequestQueue(getApplicationContext());

        StringRequest stringRequest = new StringRequest(Request.Method.POST, Server.duongdan_getdonhang,
                new Response.Listener<String>() {
                    @Override
                    public void onResponse(String response) {
                        Log.d("resp",response.toString());
                        if (response != null) {
                            try {
                                JSONArray jsonArray = new JSONArray(response);
                                Map<Integer, List<DTO_DonHang>> donHangMap = new HashMap<>();  // Sử dụng Map để nhóm các sản phẩm theo id_donhang

                                for (int i = 0; i < jsonArray.length(); i++) {
                                    JSONObject jsonObject = jsonArray.getJSONObject(i);

                                    int id_dh = jsonObject.getInt("id_donhang");
                                    String ten_sp = jsonObject.getString("ten_spmoi");
                                    String hinh_sp = jsonObject.getString("hinh_spmoi");
                                    String gia_sp = jsonObject.getString("gia_spmoi");
                                    int soluong = jsonObject.getInt("soluongct");
                                    String tongtien = jsonObject.getString("tongtien");

                                    // Tạo đối tượng DTO_DonHang
                                    DTO_DonHang donHang = new DTO_DonHang(id_dh, ten_sp, hinh_sp, gia_sp, soluong, tongtien);

                                    // Nếu đơn hàng đã tồn tại trong map, thêm sản phẩm vào danh sách sản phẩm của đơn hàng đó
                                    if (donHangMap.containsKey(id_dh)) {
                                        donHangMap.get(id_dh).add(donHang);
                                    } else {
                                        // Nếu chưa có đơn hàng trong map, tạo mới danh sách sản phẩm cho đơn hàng đó
                                        List<DTO_DonHang> sanPhamList = new ArrayList<>();
                                        sanPhamList.add(donHang);
                                        donHangMap.put(id_dh, sanPhamList);
                                    }
                                }

                                // Duyệt qua map để lấy danh sách đơn hàng hoàn chỉnh (có thể sử dụng trong RecyclerView hoặc xử lý tiếp)
                                List<DTO_DonHang> allProducts = new ArrayList<>();
                                for (List<DTO_DonHang> sanPhamList : donHangMap.values()) {
                                    allProducts.addAll(sanPhamList);
                                }

                                // Cập nhật dữ liệu vào adapter
                                list_donhang.clear();  // Xóa dữ liệu cũ
                                list_donhang.addAll(allProducts);  // Thêm dữ liệu mới
                                adapter_donHang.notifyDataSetChanged();  // Thông báo adapter cập nhật dữ liệu

                            } catch (JSONException e) {
                                e.printStackTrace();
                            }
                        }
                    }
                },
                new Response.ErrorListener() {
                    @Override
                    public void onErrorResponse(VolleyError error) {
                        Log.e("Error", error.toString());
                    }
                });

        requestQueue.add(stringRequest);
    }




    private boolean Check_Internet(Context context){
        ConnectivityManager connectivityManager=(ConnectivityManager) context.getSystemService(Context.CONNECTIVITY_SERVICE);
        NetworkInfo wifi= connectivityManager.getNetworkInfo(ConnectivityManager.TYPE_WIFI);
        NetworkInfo mobie= connectivityManager.getNetworkInfo(ConnectivityManager.TYPE_MOBILE);
        if(wifi != null && wifi.isConnected() || (mobie!= null && mobie.isConnected())){
            return  true;

        }else {
            return false;

        }
    }

}