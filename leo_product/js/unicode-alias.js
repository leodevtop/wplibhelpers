/**
 * Remove 5 Vietnamese accent / tone marks if has Combining Unicode characters
 * Tone marks: Grave (`), Acute(�), Tilde (~), Hook Above (?), Dot Bellow(.)
 */
function remove_vietnamese_accent(str)
{
	str = str.replace(/[\u0300\u0301\u0303\u0309\u0323]/g,"");
	return str;
};

/** 
 * Remove or Replace special symbols with spaces
 */
function remove_special_characters(str)
{
	str = str.replace(/[\u0021-\u002D\u002F\u003A-\u0040\u005B-\u0060\u007B-\u007E\u00A1-\u00BF]/g," ");
	return str;
};

/** 
 * Remove or Replace special symbols with spaces
 */
function replace_vietnamese_characters(str)
{
	str = str.replace(/[\u00C0-\u00C3\u00E0-\u00E3\u0102\u0103\u1EA0-\u1EB7]/g,"a");
	str = str.replace(/[\u00C8-\u00CA\u00E8-\u00EA\u1EB8-\u1EC7]/g,"e");
	str = str.replace(/[\u00CC\u00CD\u00EC\u00ED\u0128\u0129\u1EC8-\u1ECB]/g,"i");
	str = str.replace(/[\u00D2-\u00D5\u00F2-\u00F5\u01A0\u01A1\u1ECC-\u1EE3]/g,"o");
	str = str.replace(/[\u00D9-\u00DA\u00F9-\u00FA\u0168\u0169\u01AF\u01B0\u1EE4-\u1EF1]/g,"u");
	str = str.replace(/[\u00DD\u00FD\u1EF2-\u1EF9]/g,"y");
	str = str.replace(/[\u0110\u0111]/g,"d");

	return str;
};

/**
 * Create a safe Vietnamese string
 */
function safe_vietnamese(str)
{
	str = remove_vietnamese_accent(str);
	str = remove_special_characters(str);
	str = replace_vietnamese_characters(str);
	
	// Replace spaces with hyphen minus (-)
	str = str.replace(/\s+/g,"-");

	// Ensure all characters are alphanumeric or hyphen minus (-)
	str = str.replace(/[^A-Za-z0-9\-]/g,"");

	// Replace two or more hyphen minus (--) with one (-)
	str = str.replace(/\-+/g,"-");

	// Remove the hyphen minus (-) at the begining or the end
	str = str.replace(/^\-|\-$/g,"");

	// Convert to Lowercase
	str = str.toLowerCase();

	return str;
};