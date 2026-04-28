import * as React from "react"
import { CheckIcon, ChevronDownIcon, ChevronUpIcon } from "lucide-react"
import { Select as SelectPrimitive } from "radix-ui"

import { cn } from "@/lib/utils"

function Select({
  ...props
}: React.ComponentProps<typeof SelectPrimitive.Root>) {
  return <SelectPrimitive.Root data-slot="select" {...props} />
}

function SelectGroup({
  ...props
}: React.ComponentProps<typeof SelectPrimitive.Group>) {
  return <SelectPrimitive.Group data-slot="select-group" {...props} />
}

function SelectValue({
  ...props
}: React.ComponentProps<typeof SelectPrimitive.Value>) {
  return <SelectPrimitive.Value data-slot="select-value" {...props} />
}

function SelectTrigger({
  className,
  size = "default",
  children,
  ...props
}: React.ComponentProps<typeof SelectPrimitive.Trigger> & {
  size?: "sm" | "default"
}) {
  return (
    <SelectPrimitive.Trigger
      data-slot="select-trigger"
      data-size={size}
      className={cn(
        "apbl:flex apbl:w-fit apbl:items-center apbl:justify-between apbl:gap-2 apbl:rounded-md apbl:border apbl:border-input apbl:bg-transparent apbl:px-3 apbl:py-2 apbl:text-sm apbl:whitespace-nowrap apbl:shadow-xs apbl:transition-[color,box-shadow] apbl:outline-none apbl:focus-visible:border-ring apbl:focus-visible:ring-[3px] apbl:focus-visible:ring-ring/50 apbl:disabled:cursor-not-allowed apbl:disabled:opacity-50 apbl:aria-invalid:border-destructive apbl:aria-invalid:ring-destructive/20 apbl:data-[placeholder]:text-muted-foreground apbl:data-[size=default]:h-9 apbl:data-[size=sm]:h-8 apbl:*:data-[slot=select-value]:line-clamp-1 apbl:*:data-[slot=select-value]:flex apbl:*:data-[slot=select-value]:items-center apbl:*:data-[slot=select-value]:gap-2 apbl:dark:bg-input/30 apbl:dark:hover:bg-input/50 apbl:dark:aria-invalid:ring-destructive/40 apbl:[&_svg]:pointer-events-none apbl:[&_svg]:shrink-0 apbl:[&_svg:not([class*=size-])]:size-4 apbl:[&_svg:not([class*=text-])]:text-muted-foreground",
        className
      )}
      {...props}
    >
      {children}
      <SelectPrimitive.Icon asChild>
        <ChevronDownIcon className="apbl:size-4 apbl:opacity-50" />
      </SelectPrimitive.Icon>
    </SelectPrimitive.Trigger>
  )
}

function SelectContent({
  className,
  children,
  position = "item-aligned",
  align = "center",
  ...props
}: React.ComponentProps<typeof SelectPrimitive.Content>) {
  return (
    <SelectPrimitive.Portal>
      <SelectPrimitive.Content
        data-slot="select-content"
        className={cn(
          "apbl:relative apbl:z-50 apbl:max-h-(--radix-select-content-available-height) apbl:min-w-[8rem] apbl:origin-(--radix-select-content-transform-origin) apbl:overflow-x-hidden apbl:overflow-y-auto apbl:rounded-md apbl:border apbl:bg-popover apbl:text-popover-foreground apbl:shadow-md apbl:data-[side=bottom]:slide-in-from-top-2 apbl:data-[side=left]:slide-in-from-right-2 apbl:data-[side=right]:slide-in-from-left-2 apbl:data-[side=top]:slide-in-from-bottom-2 apbl:data-[state=closed]:animate-out apbl:data-[state=closed]:fade-out-0 apbl:data-[state=closed]:zoom-out-95 apbl:data-[state=open]:animate-in apbl:data-[state=open]:fade-in-0 apbl:data-[state=open]:zoom-in-95",
          position === "popper" &&
            "apbl:data-[side=bottom]:translate-y-1 apbl:data-[side=left]:-translate-x-1 apbl:data-[side=right]:translate-x-1 apbl:data-[side=top]:-translate-y-1",
          className
        )}
        position={position}
        align={align}
        {...props}
      >
        <SelectScrollUpButton />
        <SelectPrimitive.Viewport
          className={cn(
            "apbl:p-1",
            position === "popper" &&
              "apbl:h-[var(--radix-select-trigger-height)] apbl:w-full apbl:min-w-[var(--radix-select-trigger-width)] apbl:scroll-my-1"
          )}
        >
          {children}
        </SelectPrimitive.Viewport>
        <SelectScrollDownButton />
      </SelectPrimitive.Content>
    </SelectPrimitive.Portal>
  )
}

function SelectLabel({
  className,
  ...props
}: React.ComponentProps<typeof SelectPrimitive.Label>) {
  return (
    <SelectPrimitive.Label
      data-slot="select-label"
      className={cn("apbl:px-2 apbl:py-1.5 apbl:text-xs apbl:text-muted-foreground", className)}
      {...props}
    />
  )
}

function SelectItem({
  className,
  children,
  ...props
}: React.ComponentProps<typeof SelectPrimitive.Item>) {
  return (
    <SelectPrimitive.Item
      data-slot="select-item"
      className={cn(
        "apbl:relative apbl:flex apbl:w-full apbl:cursor-default apbl:items-center apbl:gap-2 apbl:rounded-sm apbl:py-1.5 apbl:pr-8 apbl:pl-2 apbl:text-sm apbl:outline-hidden apbl:select-none apbl:focus:bg-accent apbl:focus:text-accent-foreground apbl:data-[disabled]:pointer-events-none apbl:data-[disabled]:opacity-50 apbl:[&_svg]:pointer-events-none apbl:[&_svg]:shrink-0 apbl:[&_svg:not([class*=size-])]:size-4 apbl:[&_svg:not([class*=text-])]:text-muted-foreground apbl:*:[span]:last:flex apbl:*:[span]:last:items-center apbl:*:[span]:last:gap-2",
        className
      )}
      {...props}
    >
      <span
        data-slot="select-item-indicator"
        className="apbl:absolute apbl:right-2 apbl:flex apbl:size-3.5 apbl:items-center apbl:justify-center"
      >
        <SelectPrimitive.ItemIndicator>
          <CheckIcon className="apbl:size-4" />
        </SelectPrimitive.ItemIndicator>
      </span>
      <SelectPrimitive.ItemText>{children}</SelectPrimitive.ItemText>
    </SelectPrimitive.Item>
  )
}

function SelectSeparator({
  className,
  ...props
}: React.ComponentProps<typeof SelectPrimitive.Separator>) {
  return (
    <SelectPrimitive.Separator
      data-slot="select-separator"
      className={cn("apbl:pointer-events-none apbl:-mx-1 apbl:my-1 apbl:h-px apbl:bg-border", className)}
      {...props}
    />
  )
}

function SelectScrollUpButton({
  className,
  ...props
}: React.ComponentProps<typeof SelectPrimitive.ScrollUpButton>) {
  return (
    <SelectPrimitive.ScrollUpButton
      data-slot="select-scroll-up-button"
      className={cn(
        "apbl:flex apbl:cursor-default apbl:items-center apbl:justify-center apbl:py-1",
        className
      )}
      {...props}
    >
      <ChevronUpIcon className="apbl:size-4" />
    </SelectPrimitive.ScrollUpButton>
  )
}

function SelectScrollDownButton({
  className,
  ...props
}: React.ComponentProps<typeof SelectPrimitive.ScrollDownButton>) {
  return (
    <SelectPrimitive.ScrollDownButton
      data-slot="select-scroll-down-button"
      className={cn(
        "apbl:flex apbl:cursor-default apbl:items-center apbl:justify-center apbl:py-1",
        className
      )}
      {...props}
    >
      <ChevronDownIcon className="apbl:size-4" />
    </SelectPrimitive.ScrollDownButton>
  )
}

export {
  Select,
  SelectContent,
  SelectGroup,
  SelectItem,
  SelectLabel,
  SelectScrollDownButton,
  SelectScrollUpButton,
  SelectSeparator,
  SelectTrigger,
  SelectValue,
}
