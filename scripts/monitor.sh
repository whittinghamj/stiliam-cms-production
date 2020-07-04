#!/bin/bash
session="work"

# set up tmux
tmux start-server

# create a new tmux session, starting vim from a saved session in the new window
tmux new-session -d -s $session -n vim #"vim -S ~/.vim/sessions/kittybusiness"

# Select pane 1
tmux selectp -t 1 
tmux send-keys "cd /root;htop" C-m 

# Split pane 1 horizontal by 65%
tmux splitw -h -p 35
tmux send-keys "sudo nload" C-m 

# Select pane 2 
tmux selectp -t 2

# Split pane 2 vertiacally by 25%
tmux splitw -v -p 75

# select pane 3
tmux selectp -t 3
tmux send-keys "iftop" C-m 

# Select pane 1
tmux selectp -t 1

# create a new window called scratch
# tmux new-window -t $session:1 -n scratch

# return to main vim window
# tmux select-window -t $session:0

# Finished setup, attach to the tmux session!
tmux attach-session -t $session
tmux attach-session -t $session
