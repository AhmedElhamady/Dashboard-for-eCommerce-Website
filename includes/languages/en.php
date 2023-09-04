<?php
function lang($phrase)
{
    static $lang = array(
        //************* main shop ***********//

        // navbar
        "home shop" => "Home",

        // page title
        "defulte" => "Admin page",
        // navbar
        "home" => "Admin Page",
        "categories" => "Categories",
        "items" => "Items",
        "members" => "Members",
        "comments" => "Comments",
        "statistics" => "Statistics",
        "logs" => "Logs",
        "edt prof" => "Edit Profile",
        "settings" => "Settings",
        "logout" => "Logout",
        // members page
        "mng members" => "Manage Members",
        "edt member" => "Edit Member",
        "upd member" => "Update Member",
        "add member" => "Add Member",
        "new member" => "New Member",
        "del member" => "Delete Member",
        "actv member" => "Activate Member",
        "edt" => "Edit",
        "del" => "Delete",
        "username" => "Username",
        "password" => "Password",
        "email" => "Email",
        "full name" => "Full Name",
        "save" => "Save",
        "add" => "Add",
        "actv" => "Activate",
        // dashboard
        "dashboard" => "Dashboard",
        "tot member" => "Total Members",
        "pending members" => "Pending Members",
        "tot items" => "Total Items",
        "tot comments" => "Total Comments",
        "latest users" => "Latest regesterd users",
        "latest items" => "Latest items",
        "latest comments" => "Latest Comments",
        // category
        "categories" => "Categories",
        "category" => "Category",
        "mng categories" => "Manage Categories",
        "edt category" => "Edit Category",
        "upd category" => "Update Category",
        "add category" => "Add Category",
        "new category" => "New Category",
        "name" => "Name",
        "description" => "Description",
        "ordering" => "Ordering",
        "visible" => "Visible",
        "comment" => "Allow commenting",
        "ads" => "Allow Ads",
        // item
        "item" => "item",
        "add item" => "Add Item",
        "new item" => "New Item",
        "mng items" => "Manage Items",
        "edt item" => "Edit Item",
        "del item" => "Delete Item",
        "price" => "Price",
        "country" => "Country",
        "to member" => "To Member",
        "status" => "Status",
        "approve" => "Approve",
        "approve item" => "Approve Item",
        // comments
        "mng comments" => "Manage Comments",
        "edt comment" => "Edit comment",
        "del comment" => "Delete comment",
        "approve comment" => "Approve Comment",
        "view comment" => "View Comment",
        "all comments for" => "All comments for",
        "comment" => "Comment",
        "" => "",
        "" => "",
        "" => "",
        "" => "",
        "" => "",
    );
    return $lang[$phrase];
}
