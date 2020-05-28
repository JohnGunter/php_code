//Code to display image according to higharchy of specific images in file system. Starts with school specific image by school id, then tries all schools in the group id and finally displays a default image.

if( file_exists($_SERVER['DOCUMENT_ROOT'].'/images/packages/'.$_SESSION['imgFolder'].'/'.$_SESSION['school_id'].'_'.$row['package_id'].'_large.jpg') ) {
	echo '../images/packages/'.$_SESSION['imgFolder'].'/'.$_SESSION['school_id'].'_'.$row['package_id'].'_large.jpg';
} else if( file_exists($_SERVER['DOCUMENT_ROOT'].'/images/packages/'.$_SESSION['imgFolder'].'/'.$_SESSION['logo_group_id'].'_'.$row['package_id'].'_large.jpg') ) {
	echo '../images/packages/'.$_SESSION['imgFolder'].'/'.$_SESSION['logo_group_id'].'_'.$row['package_id'].'_large.jpg';
} else {
	trackMainImage('personalization',$_SESSION['school_id'],'/images/packages/'.$_SESSION['imgFolder'].'/'.$_SESSION['logo_group_id'].'_'.$row['package_id'].'_large.jpg');
	echo '../images/packages/base/'.$row['package_id'].'_large.jpg';
}


//Same code as above but using Ajax code for display in browser after page has loaded from the server and the customer is changing options.
jQuery.ajax({
	type: 'HEAD',
	url: '../images/df/large/<?=$_SESSION['imgFolder']?>/'+jQuery('#Design option:selected').val()+'~'+jQuery('#Molding option:selected').val()+'^<?=$_SESSION['logo_group_id']?>_'+logo_id+'.jpg',
	success: function() {
		jQuery('#diplomaFrameLarge').attr('src','../images/df/large/<?=$_SESSION['imgFolder']?>/'+jQuery('#Design option:selected').val()+'~'+jQuery('#Molding option:selected').val()+'^<?=$_SESSION['logo_group_id']?>_'+logo_id+'.jpg');
	},
	error: function() {
		jQuery('#diplomaFrameLarge').attr('src','../images/df/large/base/'+jQuery('#Design option:selected').val()+'~'+jQuery('#Molding option:selected').val()+'.jpg');
	}
});