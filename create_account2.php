<!DOCTYPE html>
<html lang="en">
<?php
session_start();
include('includes/dbconnection.php');

if(isset($_POST['signup']))
{ 

    if (isset($_POST['role'])) {
        $sum = array_sum($_POST['role']);
    } else{
        echo "<script>alert('Please check category.');</script>";
        return false;
    }

    $city=$_SESSION['city'];
    $country=$_SESSION['country'];
    $firstname=$_SESSION['firstname'];
    $lastname=$_SESSION['lastname'];
    $email=$_SESSION['emailid']; 
    $myaddress=$_SESSION['myaddress']; 
    $mobile=$_SESSION['mobileno'];
    $dignity='User';

    $farmname=$_POST['farmname'];
    $farmcity=$_POST['farmcity'];
    $farmaddress=$_POST['farmaddress'];
    $farmcountry=$_POST['farmcountry'];
    echo "<script>alert(".$farmcountry.");</script>";
     
    $password=md5($_POST['password']); 
    $sql="INSERT INTO  tbladmin(AdminName,FirstName,LastName,FarmName,FarmAddress, FarmCity, FarmCountry, MyAddress,City,Country,Email,MobileNumber,Password,UserRole) VALUES(:dignity,:firstname,:lastname,:farmname,:farmaddress,:farmcity,:farmcountry,:myaddress,:city,:country,:email,:mobile,:password,:sum)";
    $query = $dbh->prepare($sql);
    $query->bindParam(':city',$city,PDO::PARAM_STR);
    $query->bindParam(':country',$country,PDO::PARAM_STR);
    $query->bindParam(':firstname',$firstname,PDO::PARAM_STR);
    $query->bindParam(':lastname',$lastname,PDO::PARAM_STR);
    $query->bindParam(':email',$email,PDO::PARAM_STR);
    $query->bindParam(':dignity',$dignity,PDO::PARAM_STR);
    $query->bindParam(':farmname',$farmname,PDO::PARAM_STR);
    $query->bindParam(':farmcity',$farmcity,PDO::PARAM_STR);
    $query->bindParam(':farmcountry',$farmcountry,PDO::PARAM_STR);
    $query->bindParam(':farmaddress',$farmaddress,PDO::PARAM_STR);
    $query->bindParam(':myaddress',$myaddress,PDO::PARAM_STR);
    $query->bindParam(':mobile',$mobile,PDO::PARAM_STR);
    $query->bindParam(':password',$password,PDO::PARAM_STR);
    $query->bindParam(':sum',$sum,PDO::PARAM_STR);
    $query->execute();
    $lastInsertId = $dbh->lastInsertId();
    if($lastInsertId)
    {
        echo "<script>alert('Successfully Registered. Please wait until administrator allows this account');</script>";
        echo "<script>window.location.href = 'index.php'</script>";

    }
    else 
    {
        echo "<script>alert('Something went wrong. Please try again');</script>";
    }
    session_unset();
    session_destroy();
}

if(isset($_POST['back']))
{ 
    echo "<script>window.location.href = 'create_account1.php'</script>";
}

?>
<!-- jQuery library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<!-- jQuery UI library -->
<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.13.2/themes/smoothness/jquery-ui.css">
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.13.2/jquery-ui.min.js"></script>
<script>
    function checkAvailability() 
    {
        $("#loaderIcon").show();
        jQuery.ajax(
        {
            url: "check_availability.php",
            data:'emailid='+$("#emailid").val(),
            type: "POST",
            success:function(data)
            {
                $("#user-availability-status").html(data);
                $("#loaderIcon").hide();
            },
            error:function (){}
        });
    }
</script>

<script>
    function checkAvailability2() 
    {
        $("#loaderIcon").show();
        jQuery.ajax(
        {
            url: "check_availability.php",
            data:'fullname='+$("#fullname").val(),
            type: "POST",
            success:function(data)
            {
                $("#user-availability-status2").html(data);
                $("#loaderIcon").hide();
            },
            error:function (){}
        });
    }
</script>
<script type="text/javascript">
    function valid()
    {
        if(document.signup.password.value!= document.signup.confirmpassword.value)
        {
            alert("Password and Confirm Password Field do not match!");
            document.signup.confirmpassword.focus();
            return false;
        }
        return true;
    }
</script>
<script>
    function validateNumber() {
        const mobileno = document.querySelector("[name='mobileno']").value;
        
        if (/^\d{6,}$/.test(mobileno)) {
            return true;
        } else {
            alert("Fill the correct phone number!");
            // mobileno is invalid
            return false;
        }
    }
</script>
<?php @include("includes/head.php");?>
<body>
    <div class="container-scroller">
        <div class="container-fluid page-body-wrapper full-page-wrapper bg-image">
            <div class="content-wrapper d-flex align-items-center auth bg-img">
                <div class="row flex-grow">
                    <div class="col-lg-4 mx-auto">
                        <div class="auth-form-light text-left p-5" style="border-radius: 16px;"> 
                            <div class="brand-logo" align="center">
                                <img class="img-avatar mb-3" src="companyimages/poultrylogo.png" alt="">
                            </div>
                            <form  method="post" name="signup" onSubmit="return valid();">
                                <!-- <div class="row mb-3">
                                    <div class="form-group col-md-12">
                                        <label for="role">Role</label>
                                        <select id="role" name="roles" style="border-radius: 8px; color:black;" class="form-control" required>
                                            <option value="User" selected>User</option>
                                            <option value="Admin">Administrator</option>
                                        </select> 
                                    </div>
                                </div> -->
                                <!-- <div class="row mb-3" id="userfarm">
                                    <div class="form-group col-md-12">
                                        <label for="farmid">Farm Name</label>
                                        <select id="farmid" name="farmid" style="border-radius: 8px;" class="form-control">
                                            <option value="" selected disabled hidden>Select Farm</option>                                        
                                            <?php
                                            $sql="SELECT * from  tbladmin where tbladmin.AdminName = 'Admin' and tbladmin.Status=1 ";
                                            $query = $dbh -> prepare($sql);
                                            $query->execute();
                                            $results=$query->fetchAll(PDO::FETCH_OBJ);
                                            
                                            if($query->rowCount() > 0)
                                            {
                                            foreach($results as $rows)
                                            {
                                                ?> 
                                                <option value="<?php  echo $rows->FarmName;?>"><?php  echo $rows->FarmName;?></option>
                                                <?php 
                                            }
                                            } ?>
                                        </select>
                                    </div>
                                </div> -->
                                <div class="row mb-3">
                                    <div class="form-group col-md-6">      
                                        <input  type="radio" name="farmtype" id="newfarm" value="new" checked>
                                        <label  for="newfarm">New Farm</label>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <input  type="radio" name="farmtype" id="oldfarm" value="old">
                                        <label  for="oldfarm">Existing Farm</label>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="form-group col-md-12">
                                        <label for="farmname">Farm Name</label>
                                        <input type="text" style="border-radius: 8px;" class="form-control" name="farmname" id="farmname" placeholder="Farm Name" autocomplete="off" />
                                        <div id="farmnameList" class="farmname-list" style="display: none;"></div>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="form-group col-md-12">
                                        <label for="farmaddress">Farm Address</label>    
                                        <input type="text" style="border-radius: 8px;" class="form-control" name="farmaddress" id="farmaddress" value="<?php echo isset($_SESSION['farmaddress'])?($_SESSION['farmaddress']):""; ?>" placeholder="Farm Address" required>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="form-group col-md-6">
                                        <label for="farmcity">Farm City</label>
                                        <input type="text" style="border-radius: 8px;" class="form-control" name="farmcity" id="farmcity" value="<?php echo isset($_SESSION['farmcity'])?($_SESSION['farmcity']):""; ?>" placeholder="Farm City" required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="farmcountry">Farm Country</label>
                                        <select name="farmcountry" id="farmcountry" style="border-radius: 8px;" class="form-control" required>
                                            <option value="" selected disabled hidden>Select Country</option>
                                            <option value="Afghanistan">Afghanistan</option>
                                            <option value="Åland Islands">Åland Islands</option>
                                            <option value="Albania">Albania</option>
                                            <option value="Algeria">Algeria</option>
                                            <option value="American Samoa">American Samoa</option>
                                            <option value="Andorra">Andorra</option>
                                            <option value="Angola">Angola</option>
                                            <option value="Anguilla">Anguilla</option>
                                            <option value="Antarctica">Antarctica</option>
                                            <option value="Antigua and Barbuda">Antigua and Barbuda</option>
                                            <option value="Argentina">Argentina</option>
                                            <option value="Armenia">Armenia</option>
                                            <option value="Aruba">Aruba</option>
                                            <option value="Australia">Australia</option>
                                            <option value="Austria">Austria</option>
                                            <option value="Azerbaijan">Azerbaijan</option>
                                            <option value="Bahamas">Bahamas</option>
                                            <option value="Bahrain">Bahrain</option>
                                            <option value="Bangladesh">Bangladesh</option>
                                            <option value="Barbados">Barbados</option>
                                            <option value="Belarus">Belarus</option>
                                            <option value="Belgium">Belgium</option>
                                            <option value="Belize">Belize</option>
                                            <option value="Benin">Benin</option>
                                            <option value="Bermuda">Bermuda</option>
                                            <option value="Bhutan">Bhutan</option>
                                            <option value="Bolivia">Bolivia</option>
                                            <option value="Bosnia and Herzegovina">Bosnia and Herzegovina</option>
                                            <option value="Botswana">Botswana</option>
                                            <option value="Bouvet Island">Bouvet Island</option>
                                            <option value="Brazil">Brazil</option>
                                            <option value="British Indian Ocean Territory">British Indian Ocean Territory</option>
                                            <option value="Brunei Darussalam">Brunei Darussalam</option>
                                            <option value="Bulgaria">Bulgaria</option>
                                            <option value="Burkina Faso">Burkina Faso</option>
                                            <option value="Burundi">Burundi</option>
                                            <option value="Cambodia">Cambodia</option>
                                            <option value="Cameroon">Cameroon</option>
                                            <option value="Canada">Canada</option>
                                            <option value="Cape Verde">Cape Verde</option>
                                            <option value="Cayman Islands">Cayman Islands</option>
                                            <option value="Central African Republic">Central African Republic</option>
                                            <option value="Chad">Chad</option>
                                            <option value="Chile">Chile</option>
                                            <option value="China">China</option>
                                            <option value="Christmas Island">Christmas Island</option>
                                            <option value="Cocos (Keeling) Islands">Cocos (Keeling) Islands</option>
                                            <option value="Colombia">Colombia</option>
                                            <option value="Comoros">Comoros</option>
                                            <option value="Congo">Congo</option>
                                            <option value="Congo, The Democratic Republic of The">Congo, The Democratic Republic of The</option>
                                            <option value="Cook Islands">Cook Islands</option>
                                            <option value="Costa Rica">Costa Rica</option>
                                            <option value="Cote D'ivoire">Cote D'ivoire</option>
                                            <option value="Croatia">Croatia</option>
                                            <option value="Cuba">Cuba</option>
                                            <option value="Cyprus">Cyprus</option>
                                            <option value="Czech Republic">Czech Republic</option>
                                            <option value="Denmark">Denmark</option>
                                            <option value="Djibouti">Djibouti</option>
                                            <option value="Dominica">Dominica</option>
                                            <option value="Dominican Republic">Dominican Republic</option>
                                            <option value="Ecuador">Ecuador</option>
                                            <option value="Egypt">Egypt</option>
                                            <option value="El Salvador">El Salvador</option>
                                            <option value="Equatorial Guinea">Equatorial Guinea</option>
                                            <option value="Eritrea">Eritrea</option>
                                            <option value="Estonia">Estonia</option>
                                            <option value="Ethiopia">Ethiopia</option>
                                            <option value="Falkland Islands (Malvinas)">Falkland Islands (Malvinas)</option>
                                            <option value="Faroe Islands">Faroe Islands</option>
                                            <option value="Fiji">Fiji</option>
                                            <option value="Finland">Finland</option>
                                            <option value="France">France</option>
                                            <option value="French Guiana">French Guiana</option>
                                            <option value="French Polynesia">French Polynesia</option>
                                            <option value="French Southern Territories">French Southern Territories</option>
                                            <option value="Gabon">Gabon</option>
                                            <option value="Gambia">Gambia</option>
                                            <option value="Georgia">Georgia</option>
                                            <option value="Germany">Germany</option>
                                            <option value="Ghana">Ghana</option>
                                            <option value="Gibraltar">Gibraltar</option>
                                            <option value="Greece">Greece</option>
                                            <option value="Greenland">Greenland</option>
                                            <option value="Grenada">Grenada</option>
                                            <option value="Guadeloupe">Guadeloupe</option>
                                            <option value="Guam">Guam</option>
                                            <option value="Guatemala">Guatemala</option>
                                            <option value="Guernsey">Guernsey</option>
                                            <option value="Guinea">Guinea</option>
                                            <option value="Guinea-bissau">Guinea-bissau</option>
                                            <option value="Guyana">Guyana</option>
                                            <option value="Haiti">Haiti</option>
                                            <option value="Heard Island and Mcdonald Islands">Heard Island and Mcdonald Islands</option>
                                            <option value="Holy See (Vatican City State)">Holy See (Vatican City State)</option>
                                            <option value="Honduras">Honduras</option>
                                            <option value="Hong Kong">Hong Kong</option>
                                            <option value="Hungary">Hungary</option>
                                            <option value="Iceland">Iceland</option>
                                            <option value="India">India</option>
                                            <option value="Indonesia">Indonesia</option>
                                            <option value="Iran, Islamic Republic of">Iran, Islamic Republic of</option>
                                            <option value="Iraq">Iraq</option>
                                            <option value="Ireland">Ireland</option>
                                            <option value="Isle of Man">Isle of Man</option>
                                            <option value="Israel">Israel</option>
                                            <option value="Italy">Italy</option>
                                            <option value="Jamaica">Jamaica</option>
                                            <option value="Japan">Japan</option>
                                            <option value="Jersey">Jersey</option>
                                            <option value="Jordan">Jordan</option>
                                            <option value="Kazakhstan">Kazakhstan</option>
                                            <option value="Kenya">Kenya</option>
                                            <option value="Kiribati">Kiribati</option>
                                            <option value="Kuwait">Kuwait</option>
                                            <option value="Kyrgyzstan">Kyrgyzstan</option>
                                            <option value="Lao People's Democratic Republic">Lao People's Democratic Republic</option>
                                            <option value="Latvia">Latvia</option>
                                            <option value="Lebanon">Lebanon</option>
                                            <option value="Lesotho">Lesotho</option>
                                            <option value="Liberia">Liberia</option>
                                            <option value="Libyan Arab Jamahiriya">Libyan Arab Jamahiriya</option>
                                            <option value="Liechtenstein">Liechtenstein</option>
                                            <option value="Lithuania">Lithuania</option>
                                            <option value="Luxembourg">Luxembourg</option>
                                            <option value="Macao">Macao</option>
                                            <option value="Macedonia, The Former Yugoslav Republic of">Macedonia, The Former Yugoslav Republic of</option>
                                            <option value="Madagascar">Madagascar</option>
                                            <option value="Malawi">Malawi</option>
                                            <option value="Malaysia">Malaysia</option>
                                            <option value="Maldives">Maldives</option>
                                            <option value="Mali">Mali</option>
                                            <option value="Malta">Malta</option>
                                            <option value="Marshall Islands">Marshall Islands</option>
                                            <option value="Martinique">Martinique</option>
                                            <option value="Mauritania">Mauritania</option>
                                            <option value="Mauritius">Mauritius</option>
                                            <option value="Mayotte">Mayotte</option>
                                            <option value="Mexico">Mexico</option>
                                            <option value="Micronesia, Federated States of">Micronesia, Federated States of</option>
                                            <option value="Moldova, Republic of">Moldova, Republic of</option>
                                            <option value="Monaco">Monaco</option>
                                            <option value="Mongolia">Mongolia</option>
                                            <option value="Montenegro">Montenegro</option>
                                            <option value="Montserrat">Montserrat</option>
                                            <option value="Morocco">Morocco</option>
                                            <option value="Mozambique">Mozambique</option>
                                            <option value="Myanmar">Myanmar</option>
                                            <option value="Namibia">Namibia</option>
                                            <option value="Nauru">Nauru</option>
                                            <option value="Nepal">Nepal</option>
                                            <option value="Netherlands">Netherlands</option>
                                            <option value="Netherlands Antilles">Netherlands Antilles</option>
                                            <option value="New Caledonia">New Caledonia</option>
                                            <option value="New Zealand">New Zealand</option>
                                            <option value="Nicaragua">Nicaragua</option>
                                            <option value="Niger">Niger</option>
                                            <option value="Nigeria">Nigeria</option>
                                            <option value="Niue">Niue</option>
                                            <option value="Norfolk Island">Norfolk Island</option>
                                            <option value="Northern Mariana Islands">Northern Mariana Islands</option>
                                            <option value="Norway">Norway</option>
                                            <option value="Oman">Oman</option>
                                            <option value="Pakistan">Pakistan</option>
                                            <option value="Palau">Palau</option>
                                            <option value="Palestinian Territory, Occupied">Palestinian Territory, Occupied</option>
                                            <option value="Panama">Panama</option>
                                            <option value="Papua New Guinea">Papua New Guinea</option>
                                            <option value="Paraguay">Paraguay</option>
                                            <option value="Peru">Peru</option>
                                            <option value="Philippines">Philippines</option>
                                            <option value="Pitcairn">Pitcairn</option>
                                            <option value="Poland">Poland</option>
                                            <option value="Portugal">Portugal</option>
                                            <option value="Puerto Rico">Puerto Rico</option>
                                            <option value="Qatar">Qatar</option>
                                            <option value="Reunion">Reunion</option>
                                            <option value="Romania">Romania</option>
                                            <option value="Russian Federation">Russian Federation</option>
                                            <option value="Rwanda">Rwanda</option>
                                            <option value="Saint Helena">Saint Helena</option>
                                            <option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option>
                                            <option value="Saint Lucia">Saint Lucia</option>
                                            <option value="Saint Pierre and Miquelon">Saint Pierre and Miquelon</option>
                                            <option value="Saint Vincent and The Grenadines">Saint Vincent and The Grenadines</option>
                                            <option value="Samoa">Samoa</option>
                                            <option value="San Marino">San Marino</option>
                                            <option value="Sao Tome and Principe">Sao Tome and Principe</option>
                                            <option value="Saudi Arabia">Saudi Arabia</option>
                                            <option value="Senegal">Senegal</option>
                                            <option value="Serbia">Serbia</option>
                                            <option value="Seychelles">Seychelles</option>
                                            <option value="Sierra Leone">Sierra Leone</option>
                                            <option value="Singapore">Singapore</option>
                                            <option value="Slovakia">Slovakia</option>
                                            <option value="Slovenia">Slovenia</option>
                                            <option value="Solomon Islands">Solomon Islands</option>
                                            <option value="Somalia">Somalia</option>
                                            <option value="South Africa">South Africa</option>
                                            <option value="South Georgia and The South Sandwich Islands">South Georgia and The South Sandwich Islands</option>
                                            <option value="Spain">Spain</option>
                                            <option value="Sri Lanka">Sri Lanka</option>
                                            <option value="Sudan">Sudan</option>
                                            <option value="Suriname">Suriname</option>
                                            <option value="Svalbard and Jan Mayen">Svalbard and Jan Mayen</option>
                                            <option value="Swaziland">Swaziland</option>
                                            <option value="Sweden">Sweden</option>
                                            <option value="Switzerland">Switzerland</option>
                                            <option value="Syrian Arab Republic">Syrian Arab Republic</option>
                                            <option value="Taiwan">Taiwan</option>
                                            <option value="Tajikistan">Tajikistan</option>
                                            <option value="Tanzania, United Republic of">Tanzania, United Republic of</option>
                                            <option value="Thailand">Thailand</option>
                                            <option value="Timor-leste">Timor-leste</option>
                                            <option value="Togo">Togo</option>
                                            <option value="Tokelau">Tokelau</option>
                                            <option value="Tonga">Tonga</option>
                                            <option value="Trinidad and Tobago">Trinidad and Tobago</option>
                                            <option value="Tunisia">Tunisia</option>
                                            <option value="Turkey">Turkey</option>
                                            <option value="Turkmenistan">Turkmenistan</option>
                                            <option value="Turks and Caicos Islands">Turks and Caicos Islands</option>
                                            <option value="Tuvalu">Tuvalu</option>
                                            <option value="Uganda">Uganda</option>
                                            <option value="Ukraine">Ukraine</option>
                                            <option value="United Arab Emirates">United Arab Emirates</option>
                                            <option value="United Kingdom">United Kingdom</option>
                                            <option value="United States">United States</option>
                                            <option value="United States Minor Outlying Islands">United States Minor Outlying Islands</option>
                                            <option value="Uruguay">Uruguay</option>
                                            <option value="Uzbekistan">Uzbekistan</option>
                                            <option value="Vanuatu">Vanuatu</option>
                                            <option value="Venezuela">Venezuela</option>
                                            <option value="Viet Nam">Viet Nam</option>
                                            <option value="Virgin Islands, British">Virgin Islands, British</option>
                                            <option value="Virgin Islands, U.S.">Virgin Islands, U.S.</option>
                                            <option value="Wallis and Futuna">Wallis and Futuna</option>
                                            <option value="Western Sahara">Western Sahara</option>
                                            <option value="Yemen">Yemen</option>
                                            <option value="Zambia">Zambia</option>
                                            <option value="Zimbabwe">Zimbabwe</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="form-group col-md-6">
                                        <label for="password">Password</label>
                                        <input type="password" style="border-radius: 8px;" class="form-control" name="password" minlength="6" placeholder="Password" required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="country">Confirm Password</label>
                                        <input type="password" style="border-radius: 8px;" class="form-control" name="confirmpassword" minlength="6" placeholder="Confirm Password" required>
                                    </div>
                                </div>
                                
                                <label>Select Poultry Category</label>
                                <div class="hiddenCB checklabel text-center">
                                    <div>
                                        <input type="checkbox" id="broiler"  name="role[]" value="1" autocomplete="off"/><label for="broiler">Broiler</label>
                                        <input type="checkbox" id="layer"  name="role[]" value="10" autocomplete="off"/><label for="layer">Layer</label>
                                        <input type="checkbox" id="freerange"  name="role[]" value="100" autocomplete="off"/><label for="freerange">Free Range</label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <input type="submit" value="SIGN UP" name="signup" id="submit" class="btn btn-block btn-info btn-lg font-weight-medium auth-form-btn" style="border-radius: 8px;" onclick="return validateNumber()">
                                <div class="text-center mt-4 font-weight-light">
                                    <form method="post" name="back">
                                        <input type="submit" value="BACK" name="back" id="back" class="btn btn-block btn-info btn-lg font-weight-medium auth-form-btn" style="border-radius: 8px;">    
                                    </form>
                                    <!-- <a href="create_account1.php"> 
                                        Back
                                    </a> -->
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- content-wrapper ends -->
        </div>
        <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
    <?php @include("includes/foot.php");?>
    <!-- endinject -->

</body>
</html>
<script>
$('.btn-check').click(function(){
    if($('#broiler').is(':checked') || $('#layer').is(':checked') || $('#freerange').is(':checked'))
        $(".btn-check").attr("required", false);
    else
        $(".btn-check").attr("required", true);
});
</script>
<script>
    $('#back').click(function() {
        $("input").removeAttr("required"); 
        $("select").removeAttr("required"); 
    });
</script>
<script>
    const select = document.getElementById("farmcountry");

    select.addEventListener("change", function() {
    if (select.selectedIndex === 0) {
        select.style.color = "gray";  
    } else {
        select.style.color = "#495057";
    }
    });
</script>
<script>
    const selects = document.getElementById("country");

    selects.addEventListener("change", function() {
    if (selects.selectedIndex === 0) {
        selects.style.color = "gray";  
    } else {
        selects.style.color = "#495057";
    }
    });
</script>

<script> 
    var options = document.getElementsByTagName('option');
    var keyword = "<?php echo $_SESSION['farmcountry']?>";
    for(i=0; i<options.length; i++){
        if (options[i].value === keyword){
            options[i].selected = true;
        }
    } 
</script>


<!-- <script>
$(document).ready(function() {
  $('#farmname').on('input',function() {
    var selectedFarm = $(this).val();
    console.log(selectedFarm);
    $.ajax({
      url: 'get_farm_id.php',
      method: 'POST',
      data: {
        farmid: selectedFarm
      },
      success: function(response) {
        var farmDetails = JSON.parse(response);


        var farmAddress = farmDetails.FarmAddress;
        var farmCity = farmDetails.FarmCity;
        var farmCountry = farmDetails.FarmCountry;

        $('#farmaddress').val(farmAddress).prop('readonly', true);;
        $('#farmcity').val(farmCity).prop('readonly', true);
        $('#farmcountry').val(farmCountry);
        $('#farmcountry').css('color', 'black');
  
      },
      error: function(xhr, status, error) {
        console.log(xhr.responseText);
        $('#farmaddress').val("").prop('readonly', false);
        $('#farmcity').val("").prop('readonly', false);
        $('#farmcountry').val("");
        $('#farmcountry').css('color', '#c9c8c8');

        // Handle the error here
      }
    });
  });
});
</script> -->
<script>
  $(document).ready(function() {
    var old = false;
    function handleRadioChange() {
      if ($("#oldfarm").is(":checked")) {
       
        $('#farmname').val("");
        $('#farmaddress').val("").prop('readonly', false);
        $('#farmcity').val("").prop('readonly', false);
        $('#farmcountry').val("");
        $('#farmcountry').css('color', '#c9c8c8');
        old = true;
        console.log(old);
      } else {
        $('#farmnameList').hide();
        $('#farmname').val("");
        $('#farmaddress').val("").prop('readonly', false);
        $('#farmcity').val("").prop('readonly', false);
        $('#farmcountry').val("");
        $('#farmcountry').css('color', '#c9c8c8');
        old = false;
        console.log(old);
      }
    }

    // Add event listener to the radio buttons
    $("#newfarm, #oldfarm").on("change", handleRadioChange);


    var farmnameList = $('#farmnameList');
    farmnameList.hide(); // Hide the farmnameList initially
    var previousValue = 0;
    $('#farmname').on('input', function(event) {
        var input = $(this).val();

        if (input.length > 0) {
            $.ajax({
                url: 'get_farmnames.php',
                method: 'GET',
                data: { input: input },
                dataType: 'json',
                success: function(response) {
                   
                    if (response.length > 0) {
                        if(old)  farmnameList.show(); // Show the farmnameList if it is not empty
                        
                    } else {
                        farmnameList.hide(); // Hide the farmnameList if it is empty
                    }
                    showFarmNameSuggestions(response);
                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseText);
                    farmnameList.hide();
                    $('#farmaddress').val("").prop('readonly', false);
                    $('#farmcity').val("").prop('readonly', false);
                    $('#farmcountry').val("");
                    $('#farmcountry').css('color', '#c9c8c8');
                }
            });
        } else {
            farmnameList.hide(); // Hide the farmnameList if the input is empty
        }
        var currentValue = $(this).val();
        if (currentValue.length < previousValue.length) {
        // Input has changed to a shorter length
        $('#farmaddress').val("").prop('readonly', false);
        $('#farmcity').val("").prop('readonly', false);
        $('#farmcountry').val("");
        $('#farmcountry').css('color', '#c9c8c8');
        console.log("Input changed to a shorter length");
        }
        previousValue = currentValue;
    });
});

function showFarmNameSuggestions(suggestions) {
    var suggestionList = $('#farmnameList');
    suggestionList.empty();
    suggestions.forEach(function(suggestion) {
        var suggestionItem = $('<div></div>').text(suggestion);
        suggestionItem.on('click', function() {
            $('#farmname').val(suggestion);
            
            var selectedFarm = $('#farmname').val();
            console.log(selectedFarm);
            $.ajax({
            url: 'get_farm_id.php',
            method: 'POST',
            data: {
                farmid: selectedFarm
            },
            success: function(response) {
                var farmDetails = JSON.parse(response);

                var farmAddress = farmDetails.FarmAddress;
                var farmCity = farmDetails.FarmCity;
                var farmCountry = farmDetails.FarmCountry;

                $('#farmaddress').val(farmAddress).prop('readonly', true);;
                $('#farmcity').val(farmCity).prop('readonly', true);
                $('#farmcountry').val(farmCountry);
                $('#farmcountry').css('color', 'black');
        
            },
            error: function(xhr, status, error) {
                console.log(xhr.responseText);
                $('#farmaddress').val("").prop('readonly', false);
                $('#farmcity').val("").prop('readonly', false);
                $('#farmcountry').val("");
                $('#farmcountry').css('color', '#c9c8c8');

                // Handle the error here
            }
            });

            suggestionList.hide(); // Hide the farmnameList on suggestion selection
            // $('#farmname').trigger('input');
        });
        suggestionList.append(suggestionItem);
    });
}
</script>
<style>
    option {
    color: gray; 
    }

    option:checked {
    color: #495057;
    }
</style>
<style>
.checklabel label {
  padding: 4px 6px;
  line-height: 190%;
  outline-style: none;
  transition: all .6s;
  border: 1px solid black;
  border-radius: 10px;
}

.hiddenCB div {
  display: inline;
  margin: 0;
  padding: 0;
  list-style: none;
}

.hiddenCB input[type="checkbox"],
.hiddenCB input[type="radio"] {
  display: none;
  
}

.hiddenCB label {
  cursor: pointer;
}

.hiddenCB input[type="checkbox"]+label:hover{
  background: rgba(128, 128, 128, .8);
}

.hiddenCB input[type="checkbox"]:checked+label {
  background: rgba(128, 128, 128, .4);
}

.hiddenCB input[type="checkbox"]:checked+label:hover{
  background: rgba(128, 128, 128, 0, .7);
}

.farmname-list {
    position: absolute;
    background-color: #fff;
    border: 1px solid #ccc;
    border-radius: 4px;
    max-height: 200px;
    overflow-y: auto;
    z-index: 999;
    width: 90%;
}

.farmname-list div {
    padding: 8px;
    cursor: pointer;
}

.farmname-list div:hover {
    background-color: #f0f0f0;
}

</style>