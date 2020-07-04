<?php
// vars
$cms['ip_address']			= '159.242.105.73';
$cms['port']				= '80';
$remote['ip_address'] 		= '159.242.105.84';
$remote['ssh_port']			= '33077';
$remote['ssh_user']			= 'root';
$remote['ssh_pass']			= 'admin1372';
$remote['random_pass']		= 'admin1372';
$remote['uuid'] 			= '6c7ba7ae2983002e9b3c0bdd211f95a9';

// mark the node as installing
echo "wget -q -O /tmp/garbage http://".$cms['ip_address']."/api/\?c=force_status_change\&uuid=".$remote['uuid']."\&status=installing";
echo "\n\n";

// add the control ssh user for future commands
$cmd =  "sshpass -p'".$remote['ssh_pass']."' ssh -o StrictHostKeyChecking=no -p ".$remote['ssh_port']." ".$remote['ssh_user']."@".$remote['ip_address']." ";
$cmd .= "'useradd -p $(openssl passwd -1 ".$remote['random_pass'].") stiliam; ";
$cmd .= "wget -q -O /tmp/sudoers.sh http://stiliam.com/downloads/sudoers.sh; ";
$cmd .= "sh /tmp/sudoers.sh; ";
$cmd .= "rm -rf /tmp/sudoers.sh; ";
$cmd .= ">/dev/null 2>&1;'";
echo $cmd;
// exec($cmd);
echo "\n\n";

// upload the installer
$cmd =  "sshpass -p'".$remote['random_pass']."' ssh -o StrictHostKeyChecking=no -p ".$remote['ssh_port']." ".$remote['ssh_user']."@".$remote['ip_address']." ";
$cmd .= "'wget -q -O /tmp/stiliam_node_installer.sh http://stiliam.com/downloads/stiliam_node_installer.sh; ";
$cmd .= "sh /tmp/stiliam_node_installer.sh ".$cms['ip_address']." ".$remote['uuid']."; ";
$cmd .= ">/dev/null 2>&1;'";
echo $cmd;
// exec($cmd);
echo "\n\n";
