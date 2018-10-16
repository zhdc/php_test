<?php
	//model类
	$M = new Model('information');
	$S = new Model('statistical');
	$safe_link = $M->getsafelink();
	$result = $M->getkey($safe_link);
