#!/bin/bash
# vagrant up timing test for making spoke Mars

get_time() {
  echo $(date '+%Y-%m-%d %H:%M:%S')
}

iterations=10
cur_dir=$(pwd)
log_dir=$cur_dir/$(date '+%Y-%m-%dT%H:%M:%S')-log
work_dir=$cur_dir/../../mspoke

duration_list=""
cmd="./make_spoke.sh Mars"

# if logs directory exists
if [ ! -d $log_dir ]; then
  mkdir -p $log_dir
fi

# if mspoke directory exists
if [ ! -d $work_dir ]; then
  cd $cur_dir/../../
  echo -e "[$(get_time)] $cmd" | tee -a $log_dir/time.log >/dev/null
  echo -e "[$(get_time)] $cmd" | tee -a $log_dir/run.log && $cmd
fi

echo -e "[$(get_time)] Run timing test for $iterations iterations..." | tee -a $log_dir/time.log
for i in $(seq 1 $iterations); do
  cd $work_dir
  # write start time to log files
  current_time=$(get_time)
  echo -e "[$current_time] Run $i vagrant up" | tee -a $log_dir/time.log
  echo -e "[$current_time] Run $i vagrant up" | tee -a $log_dir/run.log >/dev/null

  # start run and calculate time
  start=$(date +%s)
  vagrant up | tee -a $log_dir/run.log >/dev/null
  duration=$(($(date +%s) - ${start}))

  # write finish time to log files
  current_time=$(get_time)
  echo -e "[$current_time] Run $i duration: ${duration} sec" | tee -a $log_dir/time.log
  echo -e "[$current_time] Run $i duration: ${duration} sec" | tee -a $log_dir/run.log >/dev/null

  # append duration to list
  duration_list="$duration\n$duration_list"

  # destroy vagrant machine and remove the .vagrant/ directory
  cd $work_dir
  vagrant destroy -f | tee -a $log_dir/run.log >/dev/null
  rm -rf .vagrant/
done

# calculate sum
total_duration=$(
  echo -e "$duration_list" |
    awk '{s+=$1} END {printf "%.2f", s}'
)
# calculate mean
average_duration=$(
  awk "BEGIN {printf \"%.2f\", $total_duration / $i}"
)
# calculate SD
standard_deviation=$(
  echo -e "$duration_list" |
    awk '{sum+=$1; sumsq+=$1*$1} END {printf "%.2f", sqrt(sumsq/(NR-2) - (sum**2/(NR-1)/(NR-2)))}'
)
# echo -e "[$(get_time)] Standard deviation: $standard_deviation sec" | tee -a $log_dir/time.log
echo -e "[$(get_time)] sum: $total_duration sec, mean: $average_duration sec, standard deviation: $standard_deviation sec" | tee -a $log_dir/time.log
